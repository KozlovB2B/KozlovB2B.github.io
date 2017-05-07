<?php

namespace app\modules\script\models;

use Yii;

/**
 * This is the model class for table "script_permissions".
 *
 * @property integer $id
 * @property integer $script_id
 * @property integer $user_id
 */
class ScriptPermissions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_permissions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['script_id', 'user_id'], 'required'],
            [['script_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('script', 'ID'),
            'script_id' => Yii::t('script', 'Script'),
            'user_id' => Yii::t('script', 'User'),
        ];
    }
}
