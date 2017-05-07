<?php

namespace app\modules\script\models;

use app\modules\user\models\profile\Operator;
use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\core\components\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "call_end_reason".
 *
 * @property integer $id
 * @property integer $account_id
 * @property string $name
 * @property integer $comment_required
 * @property integer $created_at
 * @property integer $deleted_at
 * @property string $comment_title_replacement
 * @property boolean $is_goal_reached
 */
class CallEndReason extends ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                "class" => TimestampBehavior::className(),
                "updatedAtAttribute" => false,
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call_end_reason';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'name'], 'required'],
            [['account_id', 'comment_required', 'created_at', 'deleted_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['comment_title_replacement'], 'string', 'max' => 75],
            [['is_goal_reached'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('script', 'ID'),
            'account_id' => Yii::t('script', 'Account'),
            'name' => Yii::t('script', 'Name'),
            'comment_required' => Yii::t('script', 'Сomment required'),
            'created_at' => Yii::t('script', 'Created'),
            'deleted_at' => Yii::t('script', 'Deleted'),
            'comment_title_replacement' => Yii::t('script', 'Replacement for comment'),
            'is_goal_reached' => Yii::t('script', 'Aim reached'),
        ];
    }

    /**
     * @inheritdoc
     * @return CallEndReasonQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CallEndReasonQuery(get_called_class());
    }

    /**
     * List of default call end reason
     *
     * @return CallEndReason[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getDefaultReasonsList()
    {
        $config = [
            [
                "name" => Yii::t('script', 'Aim achieved'),
                "comment_required" => false
            ],
            [
                "name" => Yii::t('script', 'The aim is not achieved but the script worked out in normal mode'),
                "comment_required" => false
            ],
            [
                "name" => Yii::t('script', 'The aim has not been achieved: no suitable options in the script'),
                "comment_required" => true
            ]
        ];

        $result = [];

        foreach ($config as $c) {
            $c["class"] = CallEndReason::className();
            $result[] = \Yii::createObject($c);
        }

        return $result;
    }

    /**
     * List of call end reasons for account
     *
     * @return array
     */
    public static function getListForCurrentAccount()
    {
        $user_id = \Yii::$app->getUser()->getId();

        if (Yii::$app->getUser()->can("user_head_manager")) {
            $account_id = $user_id;
        } else {
            /** @var Operator $op */
            $op = Operator::findOne($user_id);
            $account_id = $op->head_id;
        }

        return ArrayHelper::map(CallEndReason::find()->byAccount($account_id)->active()->orderDesc()->all(), 'id', 'name');
    }

    /**
     * List of call end reasons for account
     *
     * @return array
     */
    public static function getCommentReplacementsForCurrentAccount()
    {
        $user_id = \Yii::$app->getUser()->getId();

        if (Yii::$app->getUser()->can("user_head_manager")) {
            $account_id = $user_id;
        } else {
            /** @var Operator $op */
            $op = Operator::findOne($user_id);
            $account_id = $op->head_id;
        }

        return ArrayHelper::map(CallEndReason::find()->byAccount($account_id)->active()->orderDesc()->all(), 'id', 'comment_title_replacement');
    }

    /**
     * Has no reasons
     *
     * @param $account_id
     * @return Call|array|null
     */
    public function hasNoReasons($account_id)
    {
        return !CallEndReason::find()->byAccount($account_id)->one();
    }

    /**
     * Importing default reasons list
     *
     * @param integer $account_id
     * @return bool
     */
    public function importDefaultList($account_id)
    {
        if (CallEndReason::find()->byAccount($account_id)->active()->one()) {
            return false;
        }

        foreach ($this->getDefaultReasonsList() as $r) {
            $r->account_id = $account_id;
            $r->save(false);
        }

        return true;
    }

    public function createFirstDeletedReason()
    {
        $m = \Yii::createObject([
            "class" => CallEndReason::className(),
            "account_id" => \Yii::$app->getUser()->getId(),
            "name" => "first deleted",
            "deleted_at" => time()
        ]);
        $m->save(false);
    }
}
