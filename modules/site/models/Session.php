<?php

namespace app\modules\site\models;

use Yii;

/**
 * This is the model class for table "session".
 *
 * @property string $id
 * @property integer $expire
 * @property integer $user_id
 * @property string $ip
 * @property resource $data
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session';
    }
}
