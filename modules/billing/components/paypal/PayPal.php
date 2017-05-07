<?php
namespace app\modules\billing\components\paypal;


use app\modules\billing\components\PaymentTopUpOrder;
use app\modules\billing\models\Balance;
use app\modules\billing\models\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Webhook;
use PayPal\Exception\PayPalConfigurationException;
use yii\base\Component;
use app\modules\billing\components\PayServiceInterface;
use yii\helpers\Html;

use PayPal\Api\Address;
use PayPal\Api\CreditCard;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment as PayPalPayment;
use PayPal\Api\Transaction;
use PayPal\Api\FundingInstrument;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class Cronopay
 * @package app\modules\billing\components\cronopay
 */
class PayPal extends Component implements PayServiceInterface
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
     * @var string  Client ID
     */
    public $client_id_sandbox = 'AWSEPggibvmqe-bIYmEdavsUn0d13-hcpzCMwSQNmKnsLcVBSv8a2AaJz_wbXCD4SxcE_xJk0BFM9oK3';
    public $client_id = 'AfgjpNW4jZrAUfksPtDZzv9K6C8MyQkzpFWlfxtyTp_T4zADvSCgI1FitLeX-GoEV-MjYf0mt8JMxyMK';

    /**
     * @var string Secret key
     */
    public $secret_key_sandbox = 'ENyAFZ1PjLLtrCpieTxFThHm0qtxI0kOoviiNqEOemv0an9irnJPJH1SbrYC_6IPKzcPO59-RlPCET_H';
    public $secret_key = 'EOTFsnhzA2YEEFmkydGT2Ab-cJY--yxrIo27vnH_TLPVVE5N2wQexLgSZgf1FFE1N3onrr1uCHYT_1ow';

    /**
     * @var string Ид формы, которая будет отправляться
     */
    public $form_id = 'billing___payment__paypal_from';

    /**
     * @var string Адрес для оповещения о проведении платежа
     */
    public $cb_url = '/billing/payment/callback?component=paypal';

    /**
     * @var string Адрес куда попадает пользователь в случае успешного проведения платежа
     */
    public $success_url = '/billing';

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
     * @return ApiContext
     */
    protected function apiContext()
    {
        if (YII_ENV_DEV) {
            $apiContext = new ApiContext(new OAuthTokenCredential($this->client_id_sandbox, $this->secret_key_sandbox));
            $apiContext->setConfig([
                'log.LogEnabled' => true,
                'log.FileName' => \Yii::getAlias('@runtime') . '/logs/PayPal.log',
                'log.LogLevel' => 'FINE'
            ]);
        } else {
            $apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret_key));
            $apiContext->setConfig(['mode' => 'live']);
        }

        return $apiContext;
    }


    /**
     * Получает код формы для начала платежа
     *
     * @return string
     */
    public function getFormCode()
    {
        $system_invoice = new Invoice();
        $system_invoice->user_id = Yii::$app->getUser()->getId();
        $system_invoice->amount = $this->getPayment()->sum;
        $system_invoice->currency = 'USD';
        $system_invoice->created_at = time();

        try {

            $apiContext = $this->apiContext();


            $payer = new Payer();
            $payer->setPaymentMethod("paypal");


            // ### Itemized information
            // (Optional) Lets you specify item wise
            // information
            $item1 = new Item();
            $item1->setName('Sales Script Prompter - buy credits')
                ->setCurrency($system_invoice->currency)
                ->setQuantity(1)
                ->setPrice($system_invoice->amount);
            $itemList = new ItemList();
            $itemList->setItems(array($item1));


            // ### Amount
            // Lets you specify a payment amount.
            // You can also specify additional details
            // such as shipping, tax.
            $amount = new Amount();
            $amount->setCurrency($system_invoice->currency)
                ->setTotal($system_invoice->amount);


            // ### Transaction
            // A transaction defines the contract of a
            // payment - what is the payment for and who
            // is fulfilling it.
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription("Sales Script Prompter - buy credits")
                ->setInvoiceNumber($system_invoice->id);

            // ### Redirect urls
            // Set the urls that the buyer must be redirected to after
            // payment approval/ cancellation.
            //$baseUrl = getBaseUrl();

            $host = \Yii::$app->getRequest()->getHostInfo();

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($host . $this->cb_url)
                ->setCancelUrl($host . $this->decline_url);

            $payment = new PayPalPayment();
            $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));


            $payment->create($apiContext);
        } catch (\Exception $ex) {

            $this->_errors .= "Can not create payment: " . $ex->getMessage() . '<br/>';

            return false;
        }

        $system_invoice->pay_pal_transaction = $payment->getId();
        $system_invoice->save(false);

        return $payment->getApprovalLink();
    }

    /**
     * @param $payment_id
     * @param $payer_id
     * @return Invoice
     * @throws Exception
     */
    public function executePayment($payment_id, $payer_id)
    {
        /** @var Invoice $system_invoice */
        $system_invoice = Invoice::find()->where('pay_pal_transaction = :id AND paid_at IS NULL', ['id' => $payment_id])->one();

        if (!$system_invoice) {
            throw new NotFoundHttpException('Invoice not found!');
        }

        $apiContext = $this->apiContext();

        $execution = new PaymentExecution();
        $execution->setPayerId($payer_id);

        $payment = new PayPalPayment();
        $payment->setId($payment_id);

        $result = $payment->execute($execution, $apiContext);

        if ($result->state !== 'approved') {
            throw new Exception('Payment not approved!');
        }

        $system_invoice->paid_at = time();
        $system_invoice->update(false, ['paid_at']);

        return $system_invoice;
    }


    /**
     * @return bool Payment checking before create
     */
    public function checkPayment()
    {
        if ($this->getPayment()->sum < 19) {
            $this->getPayment()->addError('sum', \Yii::t('billing', 'Amount due to pay have to be ${0} and more', [19]));
            return false;
        }

        return true;
    }

    /**
     * Execute payment
     *
     * @return PaymentTopUpOrder
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function callback()
    {
        if (!isset($_GET['paymentId']) || !isset($_GET['PayerID'])) {
            throw new BadRequestHttpException();
        }

        $invoice = $this->executePayment($_GET['paymentId'], $_GET['PayerID']);

        if ($invoice instanceof Invoice) {
            $order = new PaymentTopUpOrder();
            $order->user = $invoice->user_id;
            $order->amount = $invoice->amount;
            $order->currency = $invoice->currency;
            $order->transaction = $_GET['paymentId'];
            $order->comment = 'Payment using PayPal. Payment id: ' . $order->transaction;
            $order->success_url = $this->success_url;
            $order->fail_url = $this->decline_url;

            return $order;
        } else {
            throw new Exception();
        }
    }
}