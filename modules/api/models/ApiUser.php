<?php

namespace app\modules\api\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ApiUser".
 *
 * @property integer $id
 * @property string $user_login
 * @property integer $account_id
 * @property integer $created_at
 */
class ApiUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ApiUser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'created_at'], 'integer'],
            [['user_login'], 'string', 'max' => 32],
        ];
    }

    /**
     * @return array List of users for report
     */
    public static function getList()
    {
        return ArrayHelper::map(ApiUser::find()->byAccount(Yii::$app->getUser()->getId())->all(), 'user_login', 'user_login');
    }

    /**
     * @inheritdoc
     * @return ApiUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApiUserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                "class" => TimestampBehavior::className(),
                "updatedAtAttribute" => false,
            ]
        ];
    }
}
