<?php

namespace app\modules\user\models;

use Yii;
use app\modules\user\models\profile\Operator;

/**
 * This is the model class for table "UserAuthLog".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $account_id
 * @property string $ip
 * @property string $user_agent
 * @property integer $created_at
 */
class UserAuthLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'UserAuthLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'account_id'], 'required'],
            [['user_id', 'account_id', 'created_at'], 'integer'],
            [['ip'], 'string', 'max' => 15],
            [['user_agent'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('site', 'ID'),
            'user_id' => Yii::t('site', 'Account id'),
            'account_id' => Yii::t('site', 'Account id'),
            'ip' => Yii::t('site', 'IP'),
            'user_agent' => Yii::t('site', 'User agent'),
            'created_at' => Yii::t('site', 'Login date'),
        ];
    }

    /**
     * @inheritdoc
     * @return UserAuthLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserAuthLogQuery(get_called_class());
    }

    /**
     * Write auth log
     */
    public function write()
    {
        $log = new UserAuthLog();
        $log->user_id = \Yii::$app->getUser()->getId();

        if (Yii::$app->getUser()->can("admin") || Yii::$app->getUser()->can("user_head_manager")) {
            $log->account_id = $log->user_id;
        } else {
            /** @var Operator $op */
            $op = Operator::findOne($log->user_id);

            if ($op) {
                $log->account_id = $op->head_id;
            } else {
                $log->account_id = $log->user_id;
            }
        }

        $log->ip = Yii::$app->getRequest()->getUserIP();
        $log->user_agent = Yii::$app->getRequest()->getUserAgent();
        $log->created_at = time();
        $log->save(false);
    }

}
