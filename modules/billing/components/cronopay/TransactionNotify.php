<?php

namespace app\modules\billing\components\cronopay;

use app\modules\user\models\User;
use Yii;

/**
 * This is the model class for table "cronopay_transaction_notify".
 *
 * @property integer $id
 * @property integer $transaction_id
 * @property string $transaction_type
 * @property string $date
 * @property string $time
 * @property string $site_id
 * @property string $product_id
 * @property double $total
 * @property string $currency
 * @property string $customer_id
 * @property string $cs1
 * @property string $cs2
 * @property string $cs3
 * @property string $ip
 * @property string $username
 * @property string $password
 * @property string $language
 * @property string $sign
 * @property string $payment_type
 * @property string $merchant_no
 * @property string $merchant_uid
 * @property string $order_id
 * @property string $email
 * @property string $country
 * @property string $name
 * @property string $city
 * @property string $street
 * @property string $phone
 * @property string $state
 * @property string $zip
 * @property string $creditcardnumber
 * @property string $cardholder
 * @property integer $expire_date
 * @property string $eci
 * @property string $card_bank_name
 * @property string $card_type
 * @property string $auth_code
 * @property integer $check_sum_passed
 * @property integer $check_sum_transaction
 * @property integer $created_at
 *
 * @property User $user
 */
class TransactionNotify extends \yii\db\ActiveRecord
{
    /**
     * @var string Ключ
     */
    public $shared_sec;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cronopay_transaction_notify';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'merchant_uid']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->date = date('Y-m-d H:i:s', strtotime($this->date));
        $this->created_at = time();

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'total', 'currency', 'merchant_uid', 'transaction_type', 'customer_id', 'sign'], 'required'],

            ['transaction_id', 'unique'],

            ['merchant_uid', 'exist', 'targetClass' => 'app\modules\billing\models\Account', 'targetAttribute' => 'id'],

            ['transaction_type', 'isValidType'],

            ['sign', 'isSignCorrect'],


            [['transaction_id', 'expire_date', 'check_sum_passed', 'check_sum_transaction', 'created_at'], 'integer'],
            [['date', 'time'], 'safe'],
            [['total'], 'number'],
            [['transaction_type', 'site_id', 'product_id', 'payment_type', 'customer_id'], 'string', 'max' => 40],
            [['currency', 'language', 'country'], 'string', 'max' => 3],
            [['cs1', 'cs2', 'cs3', 'username', 'password', 'order_id', 'email', 'city', 'street'], 'string', 'max' => 255],
            [['ip', 'zip'], 'string', 'max' => 15],
            [['sign'], 'string', 'max' => 32],
            [['merchant_no', 'merchant_uid'], 'string', 'max' => 50],
            [['name', 'cardholder', 'card_bank_name', 'card_type', 'auth_code'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 25],
            [['state', 'eci'], 'string', 'max' => 2],
            [['creditcardnumber'], 'string', 'max' => 20],
            [['transaction_id', 'transaction_type', 'date', 'time', 'site_id', 'product_id', 'total', 'currency', 'customer_id', 'cs1', 'cs2', 'cs3', 'ip', 'username', 'password', 'language', 'sign', 'payment_type', 'merchant_no', 'merchant_uid', 'order_id', 'email', 'country', 'name', 'city', 'street', 'phone', 'state', 'zip', 'creditcardnumber', 'cardholder', 'expire_date', 'eci', 'card_bank_name', 'card_type', 'auth_code', 'check_sum_passed', 'check_sum_transaction', 'created_at'], 'safe'],
        ];
    }

    /**
     * @return array Available transaction types
     */
    public static function types()
    {
        return ['Purchase', 'Rebill', 'Preauth'];
    }

    /**
     * @return bool Проверка типа транзакции
     */
    public function isValidType()
    {
        if (!in_array($this->transaction_type, TransactionNotify::types())) {
            $this->addError('transaction_type', 'Invalid type');

            return false;
        }

        return true;
    }

    /**
     * @return bool Проверка подписи
     */
    public function isSignCorrect()
    {
        if (md5($this->shared_sec . $this->customer_id . $this->transaction_id . $this->transaction_type . $this->total) != $this->sign) {
            $this->addError('sign', 'Invalid sign');

            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transaction_id' => 'ID транзакции',
            'customer_id' => 'Покупатель',
            'transaction_type' => 'Тип',
            'date' => 'Дата',
            'time' => 'Время',
            'site_id' => 'Идентификатор сайта',
            'product_id' => 'Идентификатор сайта',
            'total' => 'Сумма',
            'currency' => 'Валюта',
            'cs1' => 'Cs1',
            'cs2' => 'Cs2',
            'cs3' => 'Cs3',
            'ip' => 'ip',
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'language' => 'язык',
            'sign' => 'подпись',
            'payment_type' => 'Тип карты',
            'merchant_no' => 'Пользователь',
            'merchant_uid' => 'Пользователь',
            'order_id' => 'Вн. транзакция',
            'email' => 'email',
            'country' => 'Страна',
            'name' => 'Имя',
            'city' => 'город',
            'street' => 'адрес',
            'phone' => 'номер',
            'state' => 'Штат',
            'zip' => 'индекс',
            'creditcardnumber' => 'Номер Карты',
            'cardholder' => 'Имя и Фамилия',
            'expire_date' => 'Срок действия',
            'eci' => 'Код eci',
            'card_bank_name' => 'Название банка',
            'card_type' => 'Тип карты',
            'auth_code' => 'Код авторизации',
//            'check_sum_passed' => 'Используется только при использовании устаревшего метода аутентификации владельца карты методом контрольной суммы',
//            'check_sum_transaction' => 'Используется только при использовании устаревшего метода аутентификации владельца карты методом контрольной суммы',
            'created_at' => 'Дата уведомления',
        ];
    }
}
