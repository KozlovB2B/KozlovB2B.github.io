<?php

namespace app\modules\billing\models;

use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\user\models\User;

/**
 * This is the model class for table "ServiceUsageLog".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $service_id
 * @property double $amount
 * @property string $day
 * @property string $month
 *
 * @property User $user
 * @property UserHeadManager $userHeadManager
 * @property BankProps $bankProps
 * @property Balance $balance
 */
class ServiceUsageLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ServiceUsageLog';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHeadManager()
    {
        return $this->hasOne(UserHeadManager::className(), ['id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankProps()
    {
        return $this->hasOne(BankProps::className(), ['account_id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalance()
    {
        return $this->hasOne(Balance::className(), ['id' => 'account_id']);
    }

    /**
     * @var string Pre calculated day string
     */
    private static $_day;

    /**
     * @return bool|string Pre calculated day string
     */
    protected static function getDay()
    {
        if (self::$_day === null) {
            self::$_day = date('Y-m-d');
        }

        return self::$_day;
    }

    /**
     * @var string Pre calculated month string
     */
    private static $_month;


    /**
     * @return bool|string Pre calculated day string
     */
    protected static function getMonth()
    {
        if (self::$_month === null) {
            self::$_month = date('Y-m-01');
        }

        return self::$_month;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'service_id'], 'required'],
            [['account_id', 'service_id'], 'integer'],
            [['amount'], 'number'],
            [['day'], 'safe'],
            [['month'], 'string', 'max' => 255],
        ];
    }

    /**
     * @var array Pre-calculated amount values storage
     */
    protected static $_amount_cache = [];

    /**
     * Get amount value
     *
     * @param integer $monthly_fee
     * @return integer
     */
    protected static function calculateAmount($monthly_fee)
    {
        $days_in_month = (int)date('t');
        $current_day = (int)date('j');

        $remainder = $monthly_fee % $days_in_month;

        $amount = ($monthly_fee - $remainder) / $days_in_month;

        if ($days_in_month == $current_day) {
            $amount += $remainder;
        }

        if (empty(self::$_amount_cache[$monthly_fee])) {
            self::$_amount_cache[$monthly_fee] = $amount;
        }

        return self::$_amount_cache[$monthly_fee];
    }

    /**
     * Writes log record for today and account
     *
     * @param Account $account
     * @return bool
     */
    public static function write(Account $account)
    {
        $record = new ServiceUsageLog();
        $record->service_id = $account->rate_id;
        $record->account_id = $account->id;
        $record->amount = self::calculateAmount($account->monthly_fee);
        $record->day = self::getDay();
        $record->month = self::getMonth();

        if ($record->amount <= 0) {
            return false;
        }

        if (self::find()->where('account_id=:account_id AND day=:day', ['account_id' => $record->account_id, 'day' => $record->day])->one()) {
            return false;
        }

        return $record->save();
    }


    /**
     * Saves log for today
     */
    public static function saveLog()
    {
        $pay_accounts = Account::find()->where('monthly_fee > 0')->all();
        foreach ($pay_accounts as $a) {
            if($a->userHeadManager->division == UserHeadManager::USER_DIVISION_RUS){
                self::write($a);
            }
        }
    }
}
