<?php
namespace app\modules\billing\components;

/**
 * Class PaymentTopUpOrder An order to top up user balance
 * @package app\modules\billing\components
 */
class PaymentTopUpOrder
{
    /**
     * @var integer User balance id to top up
     */
    public $user;

    /**
     * @var float Amount
     */
    public $amount;

    /**
     * @var string the 3-letter ISO 4217 currency code indicating the default currency to use for
     */
    public $currency;

    /**
     * @var string Payment service transaction id
     */
    public $transaction;

    /**
     * @var string Operation comment
     */
    public $comment;

    /**
     * @var string If set - redirect user after success balance operation
     */
    public $success_url;

    /**
     * @var string If set - redirect user after fail balance operation
     */
    public $fail_url;
}