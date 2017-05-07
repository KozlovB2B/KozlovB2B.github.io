<?php

namespace app\modules\billing\components;

use yii\base\Component;

/**
 * Class RateChangeWithdrawalCalculator
 * calculator for rate changing
 *
 * @package app\modules\billing\components
 */
class RateChangeCalculator extends Component
{
    /**
     * @const int Seconds in month
     */
    const SECONDS_IN_MONTH = 2592000;


    /**
     * When user decides to change plan - new plan activates for a month from the moment of change.
     *
     * Before change the amount of unused money (AUM) calculates.
     *
     * Formula:
     *
     * $current_monthly_fee / 30 * ($paid_till - now)
     *
     * If AUM = $new_monthly_fee - plan activates and no operations with balance performed.
     *
     * If AUM > $new_monthly_fee - plan activates and difference accrue to user balance.
     *
     * If AUM < $new_monthly_fee - user must have enough money to activate plan otherwise plan can't be changed.
     * If enough money - plan activates and money withdraw from user balance.
     *
     *
     * @param int $current_monthly_fee
     * @param int $new_monthly_fee
     * @param int $paid_till
     * @return int How much money system have to withdraw or accrue
     */
    public static function calculate($current_monthly_fee, $new_monthly_fee, $paid_till)
    {
        $time_remain = $paid_till - time();

        $unused_money = 0;

        if ($time_remain > 0) {
            $unused_money = round($current_monthly_fee / RateChangeCalculator::SECONDS_IN_MONTH * $time_remain);
        }

        return $new_monthly_fee - $unused_money;
    }

}