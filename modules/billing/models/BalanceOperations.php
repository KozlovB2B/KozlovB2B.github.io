<?php

namespace app\modules\billing\models;

use app\modules\aff\models\Account as AffiliateAccount;
use app\modules\billing\components\PaymentTopUpOrder;
use app\modules\billing\components\WithdrawOrder;
use \app\modules\user\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use yii\base\Exception;
use app\modules\core\components\ExcelExport;
use juffin_halli\dataProviderIterator\DataProviderIterator;
use app\modules\user\models\UserHeadManager;

/**
 * This is the model class for table "balance_operations".
 *
 * @property integer $id
 * @property integer $balance_id
 * @property integer $is_accrual
 * @property integer $type_id
 * @property integer $amount
 * @property string $currency
 * @property string $comment
 * @property integer $created_at
 *
 * @property User $user
 * @property UserHeadManager $userHeadManager
 * @property BankProps $bankProps
 * @property Balance $balance
 * @property AffiliateAccount $affiliate
 */
class BalanceOperations extends ActiveRecord
{
    /**
     * @const integer Пополнение баланса пользователем
     */
    const BALANCE_OPERATION_TYPE_TOP_UP_BY_USER = 1;

    /**
     * @const integer Оплата за использование сервиса
     */
    const BALANCE_OPERATION_TYPE_WITHDRAW_FOR_USAGE = 2;

    /**
     * @const integer Начисление партнерской коммисси
     */
    const BALANCE_OPERATION_TYPE_PARTNER_COMMISSION = 3;

    /**
     * @const integer Unused money payback
     */
    const BALANCE_OPERATION_TYPE_PAYBACK_FOR_UNUSED_MONEY = 4;


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAffiliate()
    {
        return $this->hasOne(AffiliateAccount::className(), ['id' => 'balance_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'balance_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHeadManager()
    {
        return $this->hasOne(UserHeadManager::className(), ['id' => 'balance_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankProps()
    {
        return $this->hasOne(BankProps::className(), ['account_id' => 'balance_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalance()
    {
        return $this->hasOne(Balance::className(), ['id' => 'balance_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'balance_operations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance_id', 'type_id', 'amount', 'created_at', 'currency'], 'required'],
            ['balance_id', 'exist', 'targetClass' => 'app\modules\billing\models\Account', 'targetAttribute' => 'id'],
            [['balance_id', 'is_accrual', 'type_id', 'amount', 'created_at'], 'integer'],
            ['currency', 'checkEqualToBalanceCurrency'],
            [['comment'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing', 'ID'),
            'user.username' => Yii::t('billing', 'User'),
            'balance_id' => Yii::t('billing', 'User'),
            'is_accrual' => Yii::t('billing', 'Operation'),
            'type_id' => Yii::t('billing', 'Operation type'),
            'amount' => Yii::t('billing', 'Sum'),
            'currency' => Yii::t('billing', 'Currency'),
            'comment' => Yii::t('billing', 'Comment'),
            'created_at' => Yii::t('billing', 'Operation date'),
        ];
    }

    /**
     * Check operation currency to be equal with user's balance currency
     *
     * @return bool
     */
    public function checkEqualToBalanceCurrency()
    {
        if ($this->balance->currency !== $this->currency) {
            $this->addError('currency', 'Operation currency must be equal ' . $this->balance->currency);
            return false;
        }

        return true;
    }


    /**
     * @inheritdoc
     * @return BalanceOperationsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BalanceOperationsQuery(get_called_class());
    }

    /**
     * Available types
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            BalanceOperations::BALANCE_OPERATION_TYPE_TOP_UP_BY_USER => Yii::t('billing', 'Balance top up by user'),
            BalanceOperations::BALANCE_OPERATION_TYPE_WITHDRAW_FOR_USAGE => Yii::t('billing', 'Payment for service use'),
            BalanceOperations::BALANCE_OPERATION_TYPE_PARTNER_COMMISSION => Yii::t('billing', 'Affiliate commission'),
            BalanceOperations::BALANCE_OPERATION_TYPE_PAYBACK_FOR_UNUSED_MONEY => Yii::t('billing', 'Unused money payback'),
        ];
    }

    /**
     * Type name
     *
     * @param $type_id
     * @return mixed
     */
    public static function typeName($type_id)
    {
        return isset(BalanceOperations::getTypes()[$type_id]) ? BalanceOperations::getTypes()[$type_id] : $type_id;
    }

    /**
     * Current operation type name
     *
     * @return mixed
     */
    public function getTypeName()
    {
        return BalanceOperations::typeName($this->type_id);
    }

    /**
     * Accrue partner commission
     *
     * Don't pay commission if $operation was not affiliate currency
     *
     * @param BalanceOperations $operation
     * @return bool
     * @throws Exception
     */
    protected static function accruePartnerCommission(BalanceOperations $operation)
    {
        // Checking for requirements
        if ($operation->type_id !== BalanceOperations::BALANCE_OPERATION_TYPE_TOP_UP_BY_USER) {
            return true;
        }

        $aff = $operation->affiliate;

        if (!$aff) {
            return true;
        }

        if (!$aff->affiliateAccount) {
            return true;
        }

        $aff_balance = Balance::findOne($aff->affiliateAccount->id);

        if(!$aff_balance || $operation->currency !== $aff_balance->currency){
            return true;
        }

        // Calculate earning and write stat
        $percent = $aff->affiliateAccount->getPercent();
        $earned = round($operation->amount * $percent / 100);
        $aff->total_affiliate_earned += $earned;

        if($aff->hit){
            $aff->hit->incrementTotalEarned($earned);
        }

        $aff->affiliateAccount->total_earned += $earned;
        $aff->save();
        $aff->affiliateAccount->save();

        // Accrue
        $model = new BalanceOperations();
        $model->balance_id = $aff->affiliateAccount->id;
        $model->is_accrual = 1;
        $model->type_id = BalanceOperations::BALANCE_OPERATION_TYPE_PARTNER_COMMISSION;
        $model->amount = $earned;
        $model->currency = $operation->currency;

        if ($operation->currency == 'RUR') {
            $model->comment = 'Начисление партнерской коммиссии в размере ' . $percent . '% от суммы пополнения счета приведенным вами пользователем. Операция пополнения №' . $operation->id . ' сумма пополнения: ' . $operation->amount . ' р.';
        } else {
            $model->comment = 'Accrue affiliate commission ' . $percent . '% of your attracted user payment. Payment ID - ' . $operation->id . ' amount: $' . $operation->amount;
        }

        $model->created_at = time();

        if (!$model->save()) {
            throw new Exception('Cant write aff commission balance operation: ' . strip_tags(Html::errorSummary($model)));
        }

        return Balance::up($model);
    }


    /**
     * Buy credits by user
     *
     * @param PaymentTopUpOrder $order
     * @return bool
     * @throws Exception
     */
    public static function topUpBalance(PaymentTopUpOrder $order)
    {
        $model = new BalanceOperations();
        $model->balance_id = $order->user;
        $model->is_accrual = 1;
        $model->type_id = BalanceOperations::BALANCE_OPERATION_TYPE_TOP_UP_BY_USER;
        $model->amount = $order->amount;
        $model->currency = $order->currency;
        $model->comment = mb_substr($order->comment, 0, 255, 'UTF-8');
        $model->created_at = time();

        $saved = $model->save();

        if (!$saved) {
            throw new Exception('Cant write balance operation: ' . strip_tags(Html::errorSummary($model)));
        }

        if (!self::accruePartnerCommission($model)) {
            throw new Exception('Cant charge partner commission: ' . strip_tags(Html::errorSummary($model)));
        }

        return Balance::up($model);
    }

    /**
     * withdraw
     *
     * @param WithdrawOrder $order
     * @param $type_id
     * @param $comment
     * @return bool
     * @throws Exception
     */
    public static function withdraw(WithdrawOrder $order, $type_id, $comment)
    {
        $model = new BalanceOperations();
        $model->balance_id = $order->user;
        $model->is_accrual = 0;
        $model->type_id = $type_id;
        $model->amount = $order->amount;
        $model->comment = mb_substr($comment, 0, 255, 'UTF-8');
        $model->created_at = time();
        $model->currency = $order->currency;
        $saved = $model->save();

        if (!$saved) {
            throw new Exception('Cant write balance operation: ' . strip_tags(Html::errorSummary($model)));
        }

        return Balance::down($model);
    }


    /**
     * Accrue
     *
     * @param PaymentTopUpOrder $order
     * @param $type_id
     * @return bool
     * @throws Exception
     */
    public static function accrue(PaymentTopUpOrder $order, $type_id)
    {
        $model = new BalanceOperations();
        $model->balance_id = $order->user;
        $model->is_accrual = 1;
        $model->type_id = $type_id;
        $model->amount = $order->amount;
        $model->currency = $order->currency;
        $model->comment = mb_substr($order->comment, 0, 255, 'UTF-8');
        $model->created_at = time();

        $saved = $model->save();

        if (!$saved) {
            throw new Exception('Cant write balance operation: ' . strip_tags(Html::errorSummary($model)));
        }

        return Balance::up($model);
    }

    /**
     * All balance operations for user
     *
     * @return ActiveDataProvider
     */
    public static function userOperationsDataProvider($id = null)
    {
        if (!$id) {
            $id = Yii::$app->getUser()->getId();
        }

        return new ActiveDataProvider(['query' => BalanceOperations::find()->allByUser($id), 'sort' => ['defaultOrder' => ['id' => SORT_DESC]]]);
    }

    /**
     * All partner accruals for user
     *
     * @return ActiveDataProvider
     */
    public static function partnerAccrualsDataProvider()
    {
        return new ActiveDataProvider([
            'query' => BalanceOperations::find()->allByUser(Yii::$app->getUser()->getId())->andWhere('[[type_id]] = ' . BalanceOperations::BALANCE_OPERATION_TYPE_PARTNER_COMMISSION),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
    }


    public function asExcel()
    {
        $filename = 'balance_operations_report';

        $excel = new ExcelExport();
        $dp = new ActiveDataProvider([
            'query' => BalanceOperations::find(),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        Yii::$app->getDb()->createCommand("SET NAMES cp1251")->execute();
        $data = new DataProviderIterator($dp, 1000);

        $excel->totalCol = 7;
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('id')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('user.username')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('is_accrual')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('amount')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('type_id')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('comment')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('created_at')));

        $excel->GoNewLine();

        /** @var BalanceOperations $model */
        foreach ($data as $model) {
            $excel->InsertText($model->id);
            $excel->InsertText($model->user ? $model->user->username : null);
            $excel->InsertText(iconv('UTF-8', 'CP1251', Yii::t('billing', $model->is_accrual ? 'Accrual' : 'Withdrawal')));
            $excel->InsertText($model->amount);
            $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getTypeName()));
            $excel->InsertText($model->comment);
            $excel->InsertText(date('Y-m-d H:i:s', $model->created_at));
            $excel->GoNewLine();
        }

        $excel->SaveFile($filename);
    }
}
