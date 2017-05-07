<?php

namespace app\modules\billing\models;

use app\modules\billing\components\PaymentTopUpOrder;
use app\modules\billing\components\RateChangeCalculator;
use app\modules\billing\components\WithdrawOrder;
use app\modules\user\models\profile\Operator;
use Yii;
use app\modules\user\models\UserHeadManager;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\DataReader;
use yii\helpers\Html;
use yii\i18n\Formatter;
use app\modules\user\models\profile\Designer;

/**
 * This is the model class for table "billing_account".
 *
 * @property integer $id
 * @property integer $rate_id
 * @property integer $balance todo deprecated
 * @property integer $available todo deprecated
 * @property integer $hold todo deprecated
 * @property integer $is_trial
 * @property integer $trial_till
 * @property integer $paid_till
 * @property integer $min_balance
 * @property string $rate_name
 * @property integer $monthly_fee
 * @property integer $operators_threshold
 * @property integer $executions_per_day
 * @property integer $executions_per_month
 * @property integer $export_allowed
 * @property integer $blocked
 * @property integer $last_rate_change
 *
 * @property Rate $rate
 * @property UserHeadManager $userHeadManager
 * @property BankProps $props
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing_account';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHeadManager()
    {
        return $this->hasOne(UserHeadManager::className(), ['id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRate()
    {
        return $this->hasOne(Rate::className(), ['id' => 'rate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProps()
    {
        return $this->hasOne(BankProps::className(), ['account_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate_id', 'balance', 'available', 'hold', 'is_trial', 'paid_till', 'min_balance', 'monthly_fee', 'operators_threshold', 'executions_per_day', 'executions_per_month', 'export_allowed', 'blocked', 'last_rate_change'], 'integer'],
            [['rate_name', 'trial_till'], 'required'],
            [['rate_name'], 'string', 'max' => 255],
            [['operators_threshold', 'monthly_fee', 'executions_per_day', 'executions_per_month', 'export_allowed'], 'safe', 'on' => 'load_rate_data']
        ];
    }

    public function beforeSave($insert)
    {
        if (!is_numeric($this->trial_till)) {
            $this->trial_till = strtotime($this->trial_till);
        }

        if ($this->monthly_fee == 0) {
            $this->paid_till = null;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing', 'ID'),
            'rate_id' => Yii::t('billing', 'Rate'),
            'balance' => Yii::t('billing', 'Balance'),
            'available' => Yii::t('billing', 'Available'),
            'hold' => Yii::t('billing', 'Hold'),
            'is_trial' => Yii::t('billing', 'Is free trial now'),
            'trial_till' => Yii::t('billing', 'Free trial end time'),
            'paid_till' => Yii::t('billing', 'Current rate paid till'),
            'min_balance' => Yii::t('billing', 'Min balance'),
            'rate_name' => Yii::t('billing', 'Rate name'),
            'monthly_fee' => Yii::t('billing', 'Monthly fee'),
            'operators_threshold' => Yii::t('billing', 'Max active operators'),
            'executions_per_day' => Yii::t('billing', 'Script executions per day'),
            'executions_per_month' => Yii::t('billing', 'Script executions per month'),
            'export_allowed' => Yii::t('billing', 'Exporting allowed'),
            'blocked' => Yii::t('billing', 'Blocked'),
            'last_rate_change' => Yii::t('billing', 'Last rate change'),
        ];
    }

    /**
     * @var Account
     */
    protected static $_current;

    /**
     * @return Account
     */
    public static function current()
    {

        if (static::$_current === null) {
            static::$_current = static::findOne(Yii::$app->getUser()->getId());
        }

        return static::$_current;
    }

    /**
     * @inheritdoc
     * @return AccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccountQuery(get_called_class());
    }

    /**
     * Register new billing account
     *
     * @param UserHeadManager $user
     * @return bool
     */
    public static function register(UserHeadManager $user)
    {

        $exist = Account::findOne($user->id);

        if ($exist) {
            return false;
        }

        $model = new Account();
        $model->id = $user->id;
        $model->rate_id = null;
        $model->balance = 0;
        $model->available = 0;
        $model->hold = 0;
        $model->is_trial = 1;
        $model->trial_till = strtotime(date("Y-m-d 23:59:59", strtotime("+14 days")));
        $model->paid_till = null;

        $balance = new Balance();
        $balance->id = $user->id;
        $balance->balance = 0;
        $balance->currency = Yii::$app->params['currency'];

        $rate_applied = false;

        if ($default_rate = Rate::getDefault()) {
            $add = [
                'export_allowed' => 1,
                'executions_per_day' => 0,
                'executions_per_month' => 0,
                'operators_threshold' => 3
            ];

            $rate_applied = $model->applyRate($default_rate, $add, Yii::t('billing', 'Automatic plan assignment after registration.'));
        }

        if (!$rate_applied) {
            $model->save(false);
        }

        return $balance->save(false);
    }

    /**
     * Current balance
     *
     * @return bool|float
     */
    public function currentBalance()
    {
        $balance = Balance::findOne($this->id);

        if (!$balance) {
            return false;
        }

        return $balance->balance;
    }

    /**
     * @return string Indicator for menu todo move to some view
     */
    public function getMenuIndicator()
    {
        $f = new Formatter();

        if ($this->is_trial) {
            return Html::tag('span', Yii::t("billing", "Free trial till: {date}", ["date" => $f->asDate($this->trial_till)]), ["class" => "label label-success"]);
        } else {
            return Html::tag('span', Yii::t("billing", "Rate: {rate}, balance: {balance, number, currency}", ["rate" => $this->rate_name, "balance" => $this->currentBalance()]), ["class" => "label " . ($this->monthly_fee == 0 ? 'label-success' : 'label-primary')]);
        }
    }

    /**
     * @return string label for rate todo move to some view
     */
    public function getRateLabel()
    {
        return Html::tag('span', $this->rate_name, ["class" => "label " . ($this->monthly_fee == 0 ? 'label-success' : 'label-primary')]);
    }

    /**
     * Can user change his rate now or not
     *
     * @return bool
     */
    public function canChangeRateNow()
    {
        return time() - $this->last_rate_change > Rate::MIN_DELAY_BETWEEN_RATE_CHANGE;
    }

    /**
     * How much user will pay today
     *
     * @param int $days_this_month Days in this month
     * @param int $today Witch day is today
     * @param int $monthly_fee How much is monthly fee
     *
     * @return int
     */
    protected static function calculatePayToday($days_this_month, $today, $monthly_fee)
    {
        $floor = round($monthly_fee / $days_this_month);

        if ($days_this_month == $today) {
            $result = $monthly_fee - ($floor * ($days_this_month - 1));
        } else {
            $result = $floor;
        }

        return $result;
    }

    /**
     * Withdraw from users balances for service using
     */
    public static function withdrawForServiceUsing()
    {
        Yii::$app->getModule('billing');

        $last_procedure = UseWithdraw::find()->orderBy('id DESC')->one();

        if ($last_procedure && date('Y-m-d', $last_procedure->created_at) == date('Y-m-d')) {
            \Yii::error('Attempt to use withdraw twice a day last withdraw procedure was ' . date('Y-m-d', $last_procedure->created_at), __METHOD__);
            return 1;
        }

        $procedure = new UseWithdraw();

        $accounts_to_pay = Account::find()->andWhere('paid_till IS NOT NULL')->andWhere('paid_till < :now', ['now' => time()])->andWhere('monthly_fee > 0')->andWhere('is_trial = 0')->andWhere('blocked = 0')->all();

        foreach ($accounts_to_pay as $a) {

            echo $a->id . PHP_EOL;

            /** @var Balance $balance */
            $balance = Balance::findOne($a->id);

            /** @var UserHeadManager $user */
            $user = UserHeadManager::findOne($a->id);

            if ($balance->balance < $a->monthly_fee) {
                $a->applyRate(Rate::getFreeForDivision($user->division), [], Yii::t('billing', 'Fallback to free plan. Not enough money.', [], $user->division));
            } else {

                $order = new WithdrawOrder();
                $order->user = $a->id;
                $order->amount = $a->monthly_fee;
                $order->currency = $balance->currency;
                $order->comment = Yii::t('billing', 'Payment for service use', [], $user->division);

                $e = false;

                try {
                    $done = BalanceOperations::withdraw($order, BalanceOperations::BALANCE_OPERATION_TYPE_WITHDRAW_FOR_USAGE, $order->comment);
                    if (!$done) {
                        $e = 'Cant withdraw money from user ' . $a->id;
                        $procedure->errors .= "$e\n";
                        throw new Exception($e);
                    }
                } catch (Exception $ex) {
                    \Yii::error($ex->getMessage(), __METHOD__);
                }

                $a->paidTillAnotherMonth();

                $a->update(false, ['paid_till']);

                if ($e === false) {
                    $procedure->accounts++;
                    $procedure->total += $order->amount;
                }
            }
        }

        $procedure->save();

        return 0;
    }


    /**
     * Block users whose balance is below their minimum allowed balance
     *
     * @throws \yii\db\Exception
     */
    public static function blockDebtors()
    {
        if ($blocked = self::getDb()->createCommand('UPDATE ' . self::tableName() . ' SET blocked = 1 WHERE balance < min_balance AND blocked = 0')->execute() > 0) {
            \Yii::info('Users blocked today: ' . $blocked, __METHOD__);
        }

        return 0;
    }

    /**
     * End trials
     *
     * @throws \yii\db\Exception
     */
    public static function endTrials()
    {
        $ended = Account::find()->where('is_trial = 1 AND trial_till < ' . time())->all();

        /** @var Rate[] $rates */
        $rates = [];

        foreach (Rate::find()->all() as $r) {
            $rates[$r->id] = $r;
        }

        foreach ($ended as $a) {

            $rate = isset($rates[$a->rate_id]) ? $rates[$a->rate_id] : false;

            if ($rate) {
                $a->setScenario('load_rate_data');
                $a->setAttributes($rate->getAttributes());
                $a->is_trial = 0;
                $a->update(false, ['is_trial', 'operators_threshold', 'monthly_fee', 'executions_per_day', 'executions_per_month', 'export_allowed']);
            }
        }

        return 0;
    }

    /**
     * Blocked user or not
     *
     * @param int $id
     * @return bool
     */
    public static function isBlocked($id = null)
    {
        if (!$id) {
            $id = \Yii::$app->getUser()->getId();
        }

        $billing = Account::find()->where('id = ' . $id)->one();
        return $billing && $billing->blocked;
    }


    /**
     * Applies rate to itself
     *
     * @param Rate $rate Rate to apply
     * @param array $additional_data individual special changes for rate
     * @param string $comment comment for rate change log
     * @return boolean
     */
    public function applyRate(Rate $rate, array $additional_data = [], $comment = null)
    {
        if (!$this->canChangeRateNow()) {
            $this->addError('id', Yii::t('billing', 'You can change rate only 1 time per day'));
            return false;
        }

        if ($this->rate_id == $rate->id) {
            $this->addError('id', Yii::t('billing', 'You are already on this rate!'));
            return false;
        }

        $monthly_fee = $this->monthly_fee;

        $log = new BillingRateChangeHistory();
        $log->account_id = $this->id;
        $log->rate_from = $this->rate_id;
        $log->rate_to = $rate->id;
        $log->rate_from_data = json_encode($this->getAttributes());
        $log->comment = $comment;

        $this->operators_threshold = $rate->operators_threshold;
        $this->monthly_fee = $rate->monthly_fee;
        $this->executions_per_day = $rate->executions_per_day;
        $this->executions_per_month = $rate->executions_per_month;
        $this->export_allowed = $rate->export_allowed;


        foreach ($additional_data as $key => $value) {
            if (in_array($key, Rate::getAllowedAdditionalData())) {
                $this->$key = $value;
            }
        }

        $this->rate_id = $rate->id;
        $this->rate_name = $rate->name;
        $this->last_rate_change = time();

        $log->rate_to_data = json_encode($this->getAttributes());

        $t = Yii::$app->getDb()->beginTransaction();

        try {
            if (!$this->withdrawUserBalance(RateChangeCalculator::calculate($monthly_fee, $rate->monthly_fee, $this->paid_till), $rate)) {
                $t->rollBack();
                return false;
            }

            if (!$this->save()) {
                $t->rollBack();
                return false;
            }

            if (!$this->performRateRestrictions()) {
                $t->rollBack();
                return false;
            }

            if (!$log->save()) {
                $t->rollBack();
                $this->addError('id', strip_tags(Html::errorSummary($log, ['header' => false, 'footer' => false])));
                return false;
            }
        } catch (Exception $ex) {
            $this->addError('id', $ex->getMessage());
            $t->rollBack();
            return false;
        }

        $t->commit();

        return true;
    }

    /**
     * Mark account as paid till another month
     */
    public function paidTillAnotherMonth()
    {
        $this->paid_till = mktime(0, 0, 0, date("m") + 1, date('d'), date("Y"));
    }

    /**
     * Withdraw or accrue user balance according to $amount value
     *
     * @param int $amount
     * @param Rate $rate
     * @throws Exception
     * @return boolean
     */
    protected function withdrawUserBalance($amount, Rate $rate)
    {
        $this->paidTillAnotherMonth();

        $comment = Yii::t('billing', 'Change plan to {plan}', ['plan' => $rate->name]);

        if ($amount > 0) {

            /** @var Balance $balance */
            $balance = Balance::findOne($this->id);

            if ($balance->balance < $amount) {
                $this->addError('id', Yii::t('billing', 'Not enough money to change plan!'));

                return false;
            }

            $order = new WithdrawOrder();
            $order->user = $this->id;
            $order->amount = $amount;
            $order->currency = $rate->currency;
            $order->comment = $comment;

            return BalanceOperations::withdraw($order, BalanceOperations::BALANCE_OPERATION_TYPE_WITHDRAW_FOR_USAGE, $comment);
        } elseif ($amount < 0) {

            $order = new PaymentTopUpOrder();
            $order->user = $this->id;
            $order->amount = abs($amount);
            $order->currency = $rate->currency;
            $order->comment = $comment;

            return BalanceOperations::accrue($order, BalanceOperations::BALANCE_OPERATION_TYPE_PAYBACK_FOR_UNUSED_MONEY);
        } else {
            $this->paid_till = null;
        }

        return true;
    }


    /**
     * Current active operators count
     *
     * @return int|string
     */
    public function activeOperatorsCount()
    {
        $operators = Operator::find()->joinWith([
            'user' => function (ActiveQuery $query) {
                $query->andWhere('user.blocked_at IS NULL');
            }
        ])->where(Operator::tableName() . '.head_id = :id', [':id' => $this->id])->count();

        $designers = Designer::find()->joinWith([
            'user' => function (ActiveQuery $query) {
                $query->andWhere('user.blocked_at IS NULL');
            }
        ])->where(Designer::tableName() . '.head_id = :id', [':id' => $this->id])->count();

        return $operators + $designers;
    }

    /**
     * @return bool Применение ограничений по тарифу
     */
    public function performRateRestrictions()
    {
        if (!$this->id) {
            return true;
        }

        $i = 0;

        /** @var Operator[] $operators */
        $operators = Operator::find()->where('head_id = :id', [':id' => $this->id])->all();
        foreach ($operators as $o) {
            $i++;
            if ($this->operators_threshold < $i) {
                if ($o->user) {
                    $o->user->block();
                }
            }
        }

        /** @var Designer[] $operators */
        $designers = Designer::find()->where('head_id = :id', [':id' => $this->id])->all();
        foreach ($designers as $d) {
            $i++;
            if ($this->operators_threshold < $i) {
                if ($d->user) {
                    $d->user->block();
                }
            }
        }

        return true;
    }

    public function executionsLimitErrorMessage()
    {
        return Yii::t("billing", "Now you can not make calls. Calls limit on your account:") . ' ' . Yii::t("billing", "{n} calls per day", ['n' => $this->executions_per_day]) . '  ' . Yii::t("billing", "{n} calls per month", ['n' => $this->executions_per_month]);
    }

    public static function topUpUserBalance($id, $summ, $cur = 'RUR')
    {
        /** @var Balance $balance */
        $balance = Balance::findOne($id);

        if (!$balance) {
            throw new Exception('Balance not found!');
        }

        $order = new PaymentTopUpOrder();
        $order->user = $balance->id;
        $order->amount = abs($summ);
        $order->currency = $balance->currency;
        $order->comment = '';

        return BalanceOperations::accrue($order, BalanceOperations::BALANCE_OPERATION_TYPE_TOP_UP_BY_USER);
    }

}
