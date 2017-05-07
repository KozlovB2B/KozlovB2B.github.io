<?php
namespace app\modules\billing\components;
use app\modules\billing\models\Payment;

/**
 * Interface PayServiceInterface
 * @package app\modules\billing\components
 */
interface PayServiceInterface
{
    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @var string Service name
     */
    public function getName();

    /**
     * @param string $currency the 3-letter ISO 4217 currency code indicating the default currency to use for
     */
    public function setCurrency($currency);

    /**
     * @return string the 3-letter ISO 4217 currency code indicating the default currency to use for
     */
    public function getCurrency();

    /**
     * @var array Allowed divisions
     */
    public function getDivisions();

    /**
     * Set allowed divisions
     *
     * @param array $divisions
     */
    public function setDivisions(array $divisions);

    /**
     * @var string Payment form code
     */
    public function getFormCode();

    /**
     * @param Payment $payment
     * @return mixed
     */
    public function setPayment(Payment &$payment);

    /**
     * @return Payment
     */
    public function getPayment();

    /**
     * @return boolean
     */
    public function checkPayment();

    /**
     * @return string
     */
    public function getErrors();

    /**
     * @return string
     */
    public function callback();
}