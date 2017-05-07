<?php
namespace app\modules\billing\models;

use yii\base\Model;
use Yii;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Roman Agilov <agilovr@gmail.com>
 */
class Payment extends Model
{
    /**
     * @var int Payment service
     */
    public $component;

    /**
     * @var int Payment sum
     */
    public $sum;

    /**
     * @return array Current integrated payment services
     */
    public static function paymentServices()
    {
        return [
            'paypal' => [
                'class' => 'app\modules\billing\components\paypal\PayPal',
                'name' => Yii::t('billing', 'PayPal account'),
                'divisions' => [
                    'en-US'
                ],
            ],
            'cronopay' => [
                'class' => 'app\modules\billing\components\cronopay\Cronopay',
                'name' => Yii::t('billing', 'Pay by card'),
                'divisions' => [
                    'ru-RU'
                ],
            ],
            'invoicepay' => [
                'class' => 'app\modules\billing\components\invoicepay\InvoicePay',
                'name' => 'Выписать счет',
                'divisions' => [
                    'ru-RU'
                ],
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'component' => \Yii::t('billing', 'Payment method'),
            'sum' => \Yii::t('billing', 'Amount ({currency})', ['currency' => Yii::$app->params['currency']])
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['sum', 'component'], 'required'],
            [['component'], 'isActiveComponent']
        ];
    }

    /**
     * Check if component is invalid
     *
     * @return bool
     */
    public function isActiveComponent()
    {
        if (!isset(Payment::paymentServices()[$this->component])) {
            $this->addError('component', \Yii::t('billing', 'Invalid payment service'));
            return false;
        }

        return true;
    }
}