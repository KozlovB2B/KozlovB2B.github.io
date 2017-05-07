<?php

namespace app\modules\billing\components\paypal;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "PayPalInvoice".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property string $pay_pal_transaction
 * @property string $currency
 * @property integer $created_at
 * @property integer $paid_at
 * @property integer $cancelled_at
 */
class Invoice extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PayPalInvoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'amount', 'currency'], 'required'],
            [['user_id', 'amount', 'created_at', 'paid_at', 'cancelled_at'], 'integer'],
            [['pay_pal_transaction'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'amount' => 'Сумма',
            'pay_pal_transaction' => 'Номер транзакции PP',
            'currency' => 'Валюта',
            'created_at' => 'Дата',
            'paid_at' => 'Оплачено',
            'cancelled_at' => 'Отменено',
        ];
    }
}
