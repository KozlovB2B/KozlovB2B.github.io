<?php

namespace app\modules\user\models;

use app\modules\aff\models\Account as AffiliateAccount;
use app\modules\billing\models\Account as BillingAccount;
use app\modules\billing\models\Balance;
use app\modules\sales\models\UserStat;
use app\modules\user\models\profile\Designer;
use Yii;
use app\modules\user\models\profile\Operator;

/**
 * This is the model class for table "SiteUserHeadManager".
 *
 * @property integer $id
 * @property string $phone
 * @property string $comment Comment
 * @property string $scripts_created Scripts created
 * @property string $current_scripts_count Current scripts count
 * @property string $current_nodes_count Current nodes count
 * @property string $logins_today Logins today
 * @property string $logins_yesterday Logins yesterday
 * @property string $logins_week Logins week
 * @property integer $executions_today Executions today
 * @property string $executions_yesterday Executions yesterday
 * @property string $executions_week Executions week
 * @property string $last_login Last login
 * @property integer $test_executions_today Last login
 * @property integer $test_executions_this_month Test executions this month
 * @property integer $executions_this_month Executions this month
 * @property string $division Division
 * @property boolean $gift_accepted Was gist accepted?
 * @property string $editor_options
 * @property boolean $create_builds_manually
 * @property boolean $record_calls
 * @property boolean $hits_report
 *
 * Relations
 *
 * @property User $user
 * @property Balance $balance
 * @property BillingAccount $billing
 * @property AffiliateAccount $affiliate
 * @property UserStat $userStat
 */
class UserHeadManager extends \yii\db\ActiveRecord
{
    /**
     * @const User division 'Russian speakers'
     */
    const USER_DIVISION_RUS = 'ru-RU';

    /**
     * @const User division 'English speakers'
     */
    const USER_DIVISION_ENG = 'en-US';

    /**
     * @var UserHeadManager Singleton for current user head manager
     */
    protected static $_current_user;

    /**
     * Singleton function for current user head manager
     *
     * @return UserHeadManager
     */
    public static function current()
    {
        if (self::$_current_user === null) {
            self::$_current_user = UserHeadManager::findOne(Yii::$app->getUser()->getId());
        }

        return self::$_current_user;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalance()
    {
        return $this->hasOne(Balance::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserStat()
    {
        return $this->hasOne(UserStat::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBilling()
    {
        return $this->hasOne(BillingAccount::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAffiliate()
    {
        return $this->hasOne(AffiliateAccount::className(), ['id' => 'id']);
    }


    /**
     * @param $head_id
     * @return int
     */
    public static function acceptGift($head_id)
    {
        return self::updateAll(['gift_accepted' => true], "id=:id", [':id' => $head_id]);
    }

    /**
     * @param $head_id
     * @return int
     */
    public static function declineGift($head_id)
    {
        return self::updateAll(['gift_accepted' => false], "id=:id", [':id' => $head_id]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SiteUserHeadManager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_builds_manually'], 'boolean'],
            [['phone'], 'string', 'max' => 20],
            [['division'], 'string', 'max' => 5],
            [['id', 'phone'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('site', 'ID'),
            'phone' => Yii::t('site', 'Phone'),
            'division' => Yii::t('site', 'Division'),
        ];
    }


    /**
     * Increments test executions
     *
     * @param $id
     * @return int
     * @throws \yii\db\Exception
     */
    public static function incrementTestExecutionsToday($id)
    {
        self::increment($id, 'test_executions_today');
        self::increment($id, 'test_executions_this_month');
    }


    /**
     * Increments executions
     *
     * @param $id
     * @return int
     * @throws \yii\db\Exception
     */
    public static function incrementExecutionsToday($id)
    {
        self::increment($id, 'executions_today');
        self::increment($id, 'executions_this_month');
    }


    /**
     * @param $id
     * @param $attr
     * @return int
     * @throws \yii\db\Exception
     */
    protected static function increment($id, $attr)
    {
        self::getDb()->createCommand('UPDATE ' . self::tableName() . ' SET ' . $attr . ' = ' . $attr . ' + 1 WHERE id = :id', [':id' => (int)$id])->execute();
    }

    /**
     * Flush day executions sted
     *
     * @return int
     * @throws \yii\db\Exception
     */
    public static function flushExecutionCounters()
    {
        self::getDb()->createCommand('UPDATE ' . self::tableName() . ' SET executions_yesterday = executions_today')->execute();
        self::getDb()->createCommand('UPDATE ' . self::tableName() . ' SET executions_today = 0, test_executions_today = 0')->execute();
    }

    /**
     * Flush day executions sted
     *
     * @return int
     * @throws \yii\db\Exception
     */
    public static function flushExecutionCountersMonth()
    {
        self::getDb()->createCommand('UPDATE ' . self::tableName() . ' SET executions_this_month = 0, test_executions_this_month = 0')->execute();
    }

    /**
     * @return array
     */
    public static function divisions()
    {
        return [
            UserHeadManager::USER_DIVISION_RUS => 'ru-RU',
            UserHeadManager::USER_DIVISION_ENG => 'en-US',
        ];
    }


    protected static $_head_managers_by_user = [];

    /**
     * Finds head manager record by user_id.
     *
     * @param $user_id
     * @return UserHeadManager
     */
    public static function findHeadManagerByUser($user_id = null)
    {
        if (!$user_id) {
            $user_id = Yii::$app->getUser()->getId();
        }

        if (!empty(static::$_head_managers_by_user[$user_id])) {
            return static::$_head_managers_by_user[$user_id];
        }

        static::$_head_managers_by_user[$user_id] = UserHeadManager::findOne($user_id);

        if (!static::$_head_managers_by_user[$user_id]) {

            /** @var Operator $op */
            $op = Operator::findOne($user_id);

            if (!$op) {

                /** @var Designer $op */
                $op = Designer::findOne($user_id);

                if (!$op) {
                    return null;
                }
            }

            static::$_head_managers_by_user[$user_id] = UserHeadManager::findOne($op->head_id);

            if (!static::$_head_managers_by_user[$user_id]) {
                return null;
            }
        }

        return static::$_head_managers_by_user[$user_id];
    }
}
