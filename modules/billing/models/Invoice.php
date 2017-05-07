<?php

namespace app\modules\billing\models;

use app\modules\aff\models\Account as AffAccount;
use app\modules\billing\components\PaymentTopUpOrder;
use app\modules\user\models\User;
use Yii;
use app\modules\core\components\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\console\Exception;

/**
 * This is the model class for table "billing_invoice".
 *
 * @property integer $id
 * @property integer $account_id
 * @property string $name
 * @property string $status_id
 * @property string $pay_for
 * @property integer $amount
 * @property integer $created_at
 * @property integer $updated_at
 *
 *
 * Relations
 *
 * @property InvoiceBankProps $claimer
 * @property InvoiceBankProps $payer
 * @property User $user
 */
class Invoice extends ActiveRecord
{
    /**
     * @const int Status "In process"
     */
    const INVOICE_STATUS_IN_PROCESS = 1;

    /**
     * @const int Status "Paid"
     */
    const INVOICE_STATUS_IN_PAID = 2;

    /**
     * @const int Status "Cancelled"
     */
    const INVOICE_STATUS_IN_CANCELLED = 3;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClaimer()
    {
        return $this->hasOne(InvoiceBankProps::className(), ['invoice_id' => 'id'])->andWhere('is_payer = 0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayer()
    {
        return $this->hasOne(InvoiceBankProps::className(), ['invoice_id' => 'id'])->andWhere('is_payer = 1');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'account_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing_invoice';
    }

    public static function getStatuses()
    {
        return [
            Invoice::INVOICE_STATUS_IN_PROCESS => Yii::t('billing', 'In process'),
            Invoice::INVOICE_STATUS_IN_PAID => Yii::t('billing', 'Paid'),
            Invoice::INVOICE_STATUS_IN_CANCELLED => Yii::t('billing', 'Cancelled'),
        ];
    }

    public function getStatusName()
    {
        $statuses = $this->getStatuses();

        return isset($statuses[$this->status_id]) ? $statuses[$this->status_id] : $this->status_id;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'amount', 'created_at', 'updated_at'], 'integer'],
            [['pay_for'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['pay_for'], 'string', 'max' => 255]
        ];
    }

    /**
     * Generate name after save
     *
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->name) {
            $this->name = 'СД-' . $this->id . '/' . date('d-m', $this->created_at);
            $this->update(false, ['name']);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing', 'ID'),
            'account_id' => Yii::t('billing', 'Account'),
            'name' => Yii::t('billing', 'Name'),
            'status_id' => Yii::t('billing', 'Status'),
            'pay_for' => Yii::t('billing', 'Pay for'),
            'amount' => Yii::t('billing', 'Amount'),
            'created_at' => Yii::t('billing', 'Created'),
            'updated_at' => Yii::t('billing', 'Updated'),
        ];
    }

    /**
     * @inheritdoc
     * @return InvoiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvoiceQuery(get_called_class());
    }

    /**
     * All balance operations for user
     *
     * @return ActiveDataProvider
     */
    public static function userDataProvider($id = null)
    {
        if (!$id) {
            $id = Yii::$app->getUser()->getId();
        }

        return new ActiveDataProvider(['query' => Invoice::find()->allByUser($id), 'sort' => ['defaultOrder' => ['id' => SORT_DESC]]]);
    }

    /**
     * @throws \Exception
     */
    public function incrementAffHitBills(){
        /** @var AffAccount $aff */
        $aff = AffAccount::findOne($this->account_id);

        if($aff->hit){
            if(!$aff->hit->bills){
                $aff->hit->bills = 0;
            }
            $aff->hit->bills++;
            $aff->hit->update(false, ['bills']);
        }
    }

    /**
     * @throws \Exception
     */
    public function incrementAffHitBillsPaid(){
        /** @var AffAccount $aff */
        $aff = AffAccount::findOne($this->account_id);
        if($aff->hit){
            if(!$aff->hit->bills_paid){
                $aff->hit->bills_paid = 0;
            }
            $aff->hit->bills_paid++;
            $aff->hit->update(false, ['bills_paid']);
        }
    }


    public function confirm()
    {
        $t = Yii::$app->getDb()->beginTransaction();

        try {
            $this->status_id = Invoice::INVOICE_STATUS_IN_PAID;

            if (!$this->save()) {
                throw new Exception('Cant save invoice');
            }

            $order = new PaymentTopUpOrder();
            $order->user = $this->account_id;
            $order->amount = $this->amount;
            $order->transaction = $this->id;
            $order->currency = 'RUR';
            $order->comment = 'Оплата через выставление счета. Счет №' . $order->transaction . ' от ' . Yii::$app->getFormatter()->asDate($this->created_at);

            if (!BalanceOperations::topUpBalance($order)) {
                throw new Exception('Cant change balance');
            }

            $this->incrementAffHitBillsPaid();

        } catch (Exception $e) {
            $t->rollBack();
            $this->addError('id', $e->getMessage());
            return false;
        }

        $t->commit();
        return true;
    }

    public function decline()
    {
        $this->status_id = Invoice::INVOICE_STATUS_IN_CANCELLED;
        return $this->save();
    }

    /**
     * Возвращает сумму прописью
     * @author runcore
     * @uses morph(...)
     */
    public function num2str($num)
    {
        $nul = 'ноль';
        $ten = array(
            array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
            array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
        );
        $a20 = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
        $tens = array(2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
        $hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
        $unit = array( // Units
            array('копейка', 'копейки', 'копеек', 1),
            array('рубль', 'рубля', 'рублей', 0),
            array('тысяча', 'тысячи', 'тысяч', 1),
            array('миллион', 'миллиона', 'миллионов', 0),
            array('миллиард', 'милиарда', 'миллиардов', 0),
        );
        //
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
                if (!intval($v)) continue;
                $uk = sizeof($unit) - $uk - 1; // unit key
                $gender = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
                else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                // units without rub & kop
                if ($uk > 1) $out[] = $this->morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            } //foreach
        } else $out[] = $nul;
        $out[] = $this->morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
        $out[] = $kop . ' ' . $this->morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }

    /**
     * Склоняем словоформу
     * @ author runcore
     */
    public function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20) return $f5;
        $n = $n % 10;
        if ($n > 1 && $n < 5) return $f2;
        if ($n == 1) return $f1;
        return $f5;
    }
}
