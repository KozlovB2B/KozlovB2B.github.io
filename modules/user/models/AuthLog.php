<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "UserAuthLog".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property string $user_agent
 * @property integer $created_at
 */
class AuthLog extends \yii\db\ActiveRecord
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
            [['user_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['ip'], 'string', 'max' => 15],
            [['user_agent'], 'string', 'max' => 500]
        ];
    }

    /**
     * Write auth log
     */
    public function write()
    {
        $log = new AuthLog();
        $log->user_id = Yii::$app->getUser()->getId();
        $log->ip = Yii::$app->getRequest()->getUserIP();
        $log->user_agent = Yii::$app->getRequest()->getUserAgent();
        $log->created_at = time();
        $log->save(false);
    }

}
