<?php

namespace app\modules\integration\modules\zebra\models;

use app\modules\user\models\profile\Designer;
use app\modules\user\models\profile\Head;
use app\modules\user\models\profile\Operator;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "zebra_user_settings".
 *
 * @property integer $user_id
 * @property integer $number
 * @property integer $name
 */
class UserSettings extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zebra_user_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'number'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['user_id'], 'required']
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
