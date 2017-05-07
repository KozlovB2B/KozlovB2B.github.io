<?php

namespace app\modules\script\models;

use Yii;

/**
 * This is the model class for table "script_run_stat".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $script_id
 * @property string $day
 * @property integer $runs
 */
class RunStat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_run_stat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'script_id', 'runs'], 'integer'],
            [['day'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing', 'ID'),
            'user_id' => Yii::t('billing', 'User'),
            'script_id' => Yii::t('billing', 'Script'),
            'day' => Yii::t('billing', 'Day'),
            'runs' => Yii::t('billing', 'Script runs'),
        ];
    }

    /**
     * @inheritdoc
     * @return RunStatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RunStatQuery(get_called_class());
    }
}
