<?php
namespace app\modules\billing\components\cronopay;

use app\modules\billing\components\PaymentTopUpOrder;
use app\modules\billing\models\Payment;
use yii\base\Component;
use app\modules\billing\components\PayServiceInterface;
use yii\helpers\Html;

/**
 * Class Cronopay
 * @package app\modules\billing\components\cronopay
 * @see https://doc.chronopay.com/doku.php
 */
class Cronopay extends Component implements PayServiceInterface
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @var string
     */
    protected $_currency;

    /**
     * @var array
     */
    protected $_divisions = [];

    /**
     * @inheritdoc
     */
    public function setCurrency($currency)
    {
        $this->_currency = $currency;
    }

    /**
     * @inheritdoc
     */
    public function getCurrency()
    {
        return $this->_currency;
    }

    /**
     * @inheritdoc
     */
    public function setDivisions(array $divisions)
    {
        $this->_divisions = $divisions;
    }

    /**
     * @inheritdoc
     */
    public function getDivisions()
    {
        return $this->_divisions;
    }

    /**
     * @var string  это Ваш идентификатор клиента (“клиентский айди”) в нашей системе. Пожалуйста, не забывайте называть его, когда обращаетесь в нашу
     * Техническую Поддержку по телефону 8 (800) 5555-443, а также, указывать его в письмах на integration@chronopay.com
     * Client ID это группа из 6 цифр, начинается на “00”, например: 001234.
     * Ваш клиентский айди Вы можете найти в номере договора с “Хронопэй” или в личном кабинете “Хронопэй”.
     */
    public $client_id = '007622';

    /**
     * @var string это внутренний идентификатор Вашего сайта (торговой точки) в нашем процессинге. Он никак не связан с ассортиментом продуктов в Вашем интернет магазине.
     * Для Вас этот идентификатор статичен. product_id состоит из 3-х групп цифр, первая из которых совпадает с Вашим Client ID, например: 001234-0001-0001
     */
    public $product_id = '007622-0001-0001';

    /**
     * @var string В Вашей CMS также может называться “кодовое слово продавца” или “секретный ключ”). Это секретный ключ, необходимый для расчета электронной подписи платежей sign.
     */
    public $shared_sec = 'RW3d67uT8m4t08I';

    /**
     * @var string Ид формы, которая будет отправляться
     */
    public $form_id = 'billing___payment__cronopay_from';

    /**
     * @var string Адрес отправки формы
     */
    public $pay_url = 'https://payments.chronopay.com/';

    /**
     * @var string Адрес для оповещения о проведении платежа
     */
    public $cb_url = '/billing/payment/callback?component=cronopay';

    /**
     * @var string Адрес куда попадает пользователь в случае успешного проведения платежа
     */
    public $success_url = '/billing?payment=success';

    /**
     * @var string Адрес куда попадает пользователь в случае неудачного проведения платежа
     */
    public $decline_url = '/billing?payment=fail';

    /**
     * @var Payment
     */
    protected $_payment;

    /**
     * @inheritdoc
     */
    public function setPayment(Payment &$payment)
    {
        $this->_payment = $payment;
    }

    /**
     * @inheritdoc
     */
    public function getPayment()
    {
        return $this->_payment;
    }

    /**
     * @var string
     */
    protected $_errors;

    /**
     * @return string
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @var string Хост с которого приходят уведомления
     */
    public $notificator = '185.30.16.166';


    /**
     * Контрольная сумма операции используется для предотвращения возможности осуществления мошеннических действий со стороны лица, совершающего оплату, например изменения переданной суммы заказа.
     *
     * Для формирования контрольной суммы используется алгоритм хэширования md5 (32 character hexadecimal number). По умолчанию, контрольная сумма операции sign является результатом md5-хэширования следующих параметров, разделенных знаком тире (“-”):
     *
     * product_id - ID продукта в системе ChronoPay (используется для идентификации Продавца, в пользу которого будет совершаться платеж);
     * product_price - сумма заказа, которую следует оплатить Покупателю;
     * sharedsec - уникальный код, который знает только ChronoPay и Продавец.
     *
     * @param int $price
     * @return string
     */
    public function generateSign($price)
    {
        return md5($this->product_id . '-' . $price . '-' . $this->shared_sec);
    }


    /**
     * Получает код формы для начала платежа
     *
     * @return string
     */
    public function getFormCode()
    {
        $sign = $this->generateSign($this->payment->sum);

        $host = \Yii::$app->getRequest()->getHostInfo();

        $cg_url = $host . $this->cb_url;
        $success_url = $host . $this->success_url;
        $decline_url = $host . $this->decline_url;

        $merchant_uid = \Yii::$app->getUser()->getId();

        list($lg, $country) = explode('-', \Yii::$app->language);

        $country = 'RUS';

        if ($this->_currency == 'USD') {
            $country = 'USA';
        }

        $code = <<<HTML
<form  id="{$this->form_id}" action="{$this->pay_url}" method="POST">
<input type="hidden" name="merchant_uid" value="{$merchant_uid}" />
<input type="hidden" name="country" value="{$country}" />
<input type="hidden" name="language" value="{$lg}" />
<input type="hidden" name="product_id" value="{$this->product_id}" />
<input type="hidden" name="product_price" value="{$this->payment->sum}" />
<input type="hidden" name="cb_url" value="{$cg_url}" />
<input type="hidden" name="success_url" value="{$success_url}" />
<input type="hidden" name="decline_url" value="{$decline_url}" />
<input type="hidden" name="sign" value="{$sign}" />
<input type="submit" value="pay" />
</form>
HTML;

        return $code;
    }

    public function checkPayment()
    {
        $min_sum = 300;

        if(\Yii::$app->params['division'] == 'en-US'){
            $min_sum = 19;
        }

        if ($this->getPayment()->sum < $min_sum) {
            $this->getPayment()->addError('sum', \Yii::t('billing', 'Amount due to pay have to be ${0} and more', [$min_sum]));
            return false;
        }

        return true;
    }

    /**
     * Обработка нотификации от хронопей
     *
     * @return PaymentTopUpOrder|bool
     */
    public function callback()
    {
        if ($_SERVER['REMOTE_ADDR'] != $this->notificator) {
            \Yii::error('Wrong notificator cronopay', __METHOD__);
            return false;
        }

        $notify = new TransactionNotify();
        $notify->setAttributes(\Yii::$app->getRequest()->post());
        $notify->shared_sec = $this->shared_sec;

        if ($notify->save()) {
            echo 'OK';
            $order = new PaymentTopUpOrder();
            $order->user = $notify->merchant_uid;
            $order->amount = (int)$notify->total;
            $order->currency = 'RUR';
            $order->transaction = $notify->transaction_id;
            $order->comment = 'Оплата через сервис Cronopay. Номер транзакции: ' . $order->transaction;


            return $order;
        } else {
            \Yii::error('Cant write cronopay notify - ' . strip_tags(Html::errorSummary($notify)), __METHOD__);

            return false;
        }
    }
}