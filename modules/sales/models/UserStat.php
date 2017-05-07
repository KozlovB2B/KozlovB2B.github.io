<?php

namespace app\modules\sales\models;

use app\modules\billing\models\Balance;
use app\modules\script\models\Call;
use app\modules\script\models\ar\Script;
use app\modules\user\models\UserAuthLog;
use Yii;
use yii\base\Exception;
use app\modules\user\models\User;
use app\modules\user\models\UserHeadManager;

/**
 * This is the model class for table "SalesUserStat".
 *
 * @property integer $id
 * @property integer $current_balance
 * @property string $comment
 * @property integer $scripts_created
 * @property integer $current_scripts_count
 * @property integer $current_nodes_count
 * @property integer $logins_today
 * @property integer $logins_yesterday
 * @property integer $logins_week
 * @property integer $executions_today
 * @property integer $executions_yesterday
 * @property integer $executions_week
 * @property integer $last_login
 *
 *
 * @property User $user
 * @property UserHeadManager $userHeadManager
 */
class UserStat extends \yii\db\ActiveRecord
{
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
    public function getUserHeadManager()
    {
        return $this->hasOne(UserHeadManager::className(), ['id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SalesUserStat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['current_balance', 'scripts_created', 'current_scripts_count', 'current_nodes_count', 'logins_today', 'logins_yesterday', 'logins_week', 'executions_today', 'executions_yesterday', 'executions_week', 'last_login'], 'integer'],
            [['comment'], 'string', 'max' => 5000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'current_balance' => 'Баланс',
            'comment' => 'Comment',
            'scripts_created' => 'Всего скриптов',
            'current_scripts_count' => 'Сейчас скриптов',
            'current_nodes_count' => 'Суммарное кол-во узлов сейчас',
            'logins_today' => 'Логинов сегодня',
            'logins_yesterday' => 'Логинов вчера',
            'logins_week' => 'Логинов за неделю',
            'executions_today' => 'Прогонов сегодня',
            'executions_yesterday' => 'Прогонов вчера',
            'executions_week' => 'Прогонов за неделю',
            'last_login' => 'Последняя авторизация',
        ];
    }

    /**
     * @inheritdoc
     * @return UserStatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserStatQuery(get_called_class());
    }

    /**
     * Aggregating stat by specified user
     *
     * @param integer $id User's id
     * @throws Exception
     */
    public static function aggregateByUser($id)
    {
        $model = UserStat::findOne($id);

        if (!$model) {
            $model = new UserStat();
            $model->id = $id;
        }

        $model->current_balance = Balance::findOne($id)->balance;
        $model->scripts_created = Script::find()->allByUserCriteria($id)->count();
        $model->current_scripts_count = Script::find()->activeByUserCriteria($id)->count();
        $model->current_nodes_count = Script::find()->activeByUserCriteria($id)->sum('nodes_count');
        $model->executions_today = Call::find()->byAccount($id)->andWhere(['between', 'started_at', strtotime('today'), strtotime("tomorrow") - 1])->count();
        $model->executions_yesterday = Call::find()->byAccount($id)->andWhere(['between', 'started_at', strtotime('yesterday'), strtotime("today") - 1])->count();
        $model->executions_week = Call::find()->byAccount($id)->andWhere(['between', 'started_at', strtotime('monday this week'), strtotime("monday next week") - 1])->count();
        $model->logins_today = UserAuthLog::find()->byAccount($id)->andWhere(['between', 'created_at', strtotime('today'), strtotime("tomorrow") - 1])->count();
        $model->logins_yesterday = UserAuthLog::find()->byAccount($id)->andWhere(['between', 'created_at', strtotime('yesterday'), strtotime("today") - 1])->count();
        $model->logins_week = UserAuthLog::find()->byAccount($id)->andWhere(['between', 'created_at', strtotime('monday this week'), strtotime("monday next week") - 1])->count();
        $model->last_login = UserAuthLog::find()->byAccount($id)->max('created_at');

        foreach ($model->getAttributes() as $attr => $val) {
            if (!$model->$attr) {
                $model->$attr = 0;
            }
        }

        $model->save(false);
    }
}
