<?php

namespace app\modules\billing\models;

use app\modules\user\models\UserHeadManager;
use Yii;
use yii\base\Component;

/**
 * This is the model class for table "ServiceUsageLog".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $service_id
 * @property double $amount
 * @property string $day
 * @property string $month
 */
class ServiceUsageLogRecreater extends Component
{
    /**
     * @var
     */
    protected static $_to_pay;
    protected static $_rates;

    public static function recreate()
    {
        ServiceUsageLog::deleteAll();

        $first = BillingRateChangeHistory::find()->where('rate_from IS NOT NULL')->one();

        $current = strtotime(date('Y-m-d', $first->created_at));
//        $current = strtotime('2016-07-01');
        $end = time();


        while ($current < $end) {

            echo 'День: ' . date('Y-m-d', $current) . PHP_EOL;

            $next = strtotime(date('Y-m-d', strtotime('+1 day', $current)));

            $jumps = BillingRateChangeHistory::find()->where('rate_from IS NOT NULL')->andWhere('created_at BETWEEN :start AND :end', ['start' => $current, 'end' => $next])->all();

            if ($jumps) {
                foreach ($jumps as $jump) {

                    if ($jump->userHeadManager->division != UserHeadManager::USER_DIVISION_RUS) {
                        echo 'Отмена добавления пользователя - потому что не русский' . PHP_EOL;
                    } else {

                        if (empty(self::$_to_pay[$jump->account_id])) {
                            self::$_to_pay[$jump->account_id] = $jump->rateTo->monthly_fee;
                            self::$_rates[$jump->account_id] = $jump->rate_to;
                            echo 'Добавлен пользователь ' . $jump->user->username . ' с месячной платой в ' . $jump->rateTo->monthly_fee . 'р. ';
                        } elseif (self::$_to_pay[$jump->account_id] != $jump->rateTo->monthly_fee) {
                            echo 'Измененена месячная плата пользователя ' . $jump->user->username . ' с ' . self::$_to_pay[$jump->account_id] . 'р. на ' . $jump->rateTo->monthly_fee . 'р. ';
                            self::$_to_pay[$jump->account_id] = $jump->rateTo->monthly_fee;
                            self::$_rates[$jump->account_id] = $jump->rate_to;
                        }

                        if ($jump->rateTo->monthly_fee <= 0) {
                            unset(self::$_to_pay[$jump->account_id]);
                            unset(self::$_rates[$jump->account_id]);
                        }
                    }

                }
            }

            foreach (self::$_to_pay as $user_id => $monthly_fee) {
                self::write($user_id, $current);
            }


            $current = $next;
        }
    }


    /**
     * @param $monthly_fee
     * @param $day
     * @return float|int
     */
    protected static function calculateAmount($monthly_fee, $day)
    {
        $days_in_month = (int)date('t', $day);
        $current_day = (int)date('j', $day);

        $remainder = $monthly_fee % $days_in_month;

        $amount = ($monthly_fee - $remainder) / $days_in_month;

        if ($days_in_month == $current_day) {
            $amount += $remainder;
        }

        return $amount;
    }

    /**
     * @param $account_id
     * @param $day
     * @return bool
     */
    public static function write($account_id, $day)
    {
        $record = new ServiceUsageLog();
        $record->service_id = self::$_rates[$account_id];
        $record->account_id = $account_id;
        $record->amount = self::calculateAmount(self::$_to_pay[$account_id], $day);
        $record->day = date('Y-m-d', $day);
        $record->month = date('Y-m-01', $day);

        if ($record->amount <= 0) {
            return false;
        }

        if (ServiceUsageLog::find()->where('account_id=:account_id AND day=:day', ['account_id' => $record->account_id, 'day' => $record->day])->one()) {
            echo 'Ошибка! Такая запись уже есть!';
            var_dump($record->getAttributes());
            return false;
        }

        echo 'Пользователь ' . $record->account_id . ' потребил в день ' . $record->day . ' на сумму ' . $record->amount . ' р.' . PHP_EOL;

        return $record->save();
    }
}
