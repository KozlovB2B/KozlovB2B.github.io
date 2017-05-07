<?php
namespace app\modules\billing\components\invoicepay;

use app\modules\aff\models\Account as AffAccount;
use app\modules\billing\models\Invoice;
use app\modules\billing\models\Payment;
use yii\base\Component;
use app\modules\billing\models\BankProps;
use Yii;
use app\modules\billing\models\InvoiceBankProps;
use yii\base\Exception;
use yii\helpers\Html;
use app\modules\billing\components\PayServiceInterface;

/**
 * Class InvoicePay
 * @package app\modules\billing\components\invoicepay;
 */
class InvoicePay extends Component implements PayServiceInterface
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

    /** @var Payment */
    public $props;


    public function checkPayment()
    {
        /** @var BankProps $props */
        $this->props = BankProps::find()->andWhere('account_id = ' . Yii::$app->user->getId())->one();

        if (!$this->props) {
            $this->getPayment()->addError('component', 'Для выписывания счетов нужно заполнить реквизиты!');
            return false;
        }

        if ($this->getPayment()->sum < 3000) {
            $this->getPayment()->addError('sum', 'Счет можно выписать только на сумму от 3000 рублей!');
            return false;
        }

        return true;
    }

    /**
     * @return Invoice
     */
    protected function createInvoice()
    {

        $t = Yii::$app->getDb()->beginTransaction();

        try {

            $invoice = new Invoice();
            $invoice->amount = $this->getPayment()->sum;
            $invoice->status_id = Invoice::INVOICE_STATUS_IN_PROCESS;
            $invoice->account_id = Yii::$app->user->getId();
            $invoice->pay_for = 'Доступ к онлайн-сервису Скрипт-Дизайнер';
            if (!$invoice->save()) {
                throw new Exception(Html::errorSummary($invoice));
            }

            $invoice->incrementAffHitBills();

            $payer_props = new InvoiceBankProps();
            $payer_props->invoice_id = $invoice->id;
            $payer_props->is_payer = 1;

            $props_attributes = $this->props->getAttributes();
            unset($props_attributes['id']);
            unset($props_attributes['account_id']);
            unset($props_attributes['created_at']);
            unset($props_attributes['updated_at']);
            $payer_props->setAttributes($props_attributes);

            if (!$payer_props->save()) {
                throw new Exception(Html::errorSummary($payer_props));
            }

            // select id,invoice_id,is_payer,first_name,contact_phone,inn, kpp, ogrn,corr_score, pay_score from billing_invoice_bank_props;
            // select id,invoice_id,is_payer,inn, kpp, ogrn,corr_score, pay_score from billing_invoice_bank_props where invoice_id = 2;
            $claimer_props = new InvoiceBankProps();
            $claimer_props->invoice_id = $invoice->id;
            $claimer_props->is_payer = 0;
            $claimer_props->first_name = 'Наталья';
            $claimer_props->last_name = 'Ярополова';
            $claimer_props->middle_name = 'Александровна';
            $claimer_props->contact_phone = '+79165620175';
            $claimer_props->company_name = 'Индивидуальный предприниматель Ярополова Наталья Александровна';
            $claimer_props->inn = '500401747280';
            $claimer_props->kpp = '';
            $claimer_props->ogrn = '311500402500021';
            $claimer_props->bank_name = 'АО "АЛЬФА-БАНК", г. МОСКВА';
            $claimer_props->bik = '044525593';
            $claimer_props->corr_score = '30101810200000000593';
            $claimer_props->pay_score = '40802810502760000170';
            $claimer_props->boss_position = 'директор';
            $claimer_props->boss_last_name = 'Ярополова';
            $claimer_props->boss_first_name = 'Наталья';
            $claimer_props->boss_middle_name = 'Александровна';
            $claimer_props->acting_on_the_basis = 'Устава';
            $claimer_props->post_address = 'Московская область, Волоколамский р-н, Федюково д, 5';

            if (!$claimer_props->save()) {
                throw new Exception(Html::errorSummary($claimer_props));
            }

            $t->commit();

            return $invoice;
        } catch (Exception $ex) {
            $this->_errors = $ex->getMessage();
            $t->rollBack();
            return false;
        }
    }

    /**
     * Получает код формы для начала платежа
     *
     * @return string
     */
    public function getFormCode()
    {
        $invoice = $this->createInvoice();

        if (!$invoice) {
            return false;
        }

        return '/billing/invoice/view?id=' . $invoice->id;
    }

    /**
     * @return bool
     */
    public function callback()
    {
        return false;
    }
}