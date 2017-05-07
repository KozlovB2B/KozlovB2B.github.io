<?php

namespace app\modules\integration\modules\onlinepbx\models;

use app\modules\user\models\profile\Operator;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\helpers\ArrayHelper;
use app\modules\user\models\profile\Head;
use app\modules\user\models\profile\Designer;

/**
 * This is the model class for table "onlinepbx_user_settings".
 *
 * @property integer $user_id
 * @property integer $number
 */
class UserSettings extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'onlinepbx_user_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'number'], 'integer'],
            [['user_id', 'number'], 'required']
        ];
    }

    /**
     * @return \app\modules\site\models\Instruction[]|array
     */
    public static function usersList()
    {
        $hm = UserHeadManager::findHeadManagerByUser();
        return  ArrayHelper::merge([Head::findOne($hm->id)], Operator::find()->where('head_id=' . $hm->id)->all(), Designer::find()->where('head_id=' . $hm->id)->all());
    }

    /**
     * @param $user_id
     * @return UserSettings
     */
    public static function settings($user_id)
    {
        return UserSettings::find()->andWhere('user_id = :user', ['user' => $user_id])->one();
    }
}
