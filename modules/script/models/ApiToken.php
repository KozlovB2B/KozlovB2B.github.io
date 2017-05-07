<?php

namespace app\modules\script\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "script_api_token".
 *
 * @property integer $id
 * @property string $token
 */
class ApiToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_api_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token'], 'string', 'max' => 64],
            [['token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
        ];
    }

    /**
     * Get token model by token
     *
     * @param string $token
     * @return ApiToken
     */
    public static function getByToken($token)
    {
        return ApiToken::find()->where('token=:token', ['token' => $token])->one();
    }

    /**
     * Get token model by id
     *
     * @param string $id
     * @return ApiToken
     */
    public static function getByUser($id)
    {
        return ApiToken::find()->where('id=:id', ['id' => $id])->one();
    }

    /**
     * Generates api token for user
     *
     * @return bool
     * @throws Exception
     */
    public static function generate()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            return false;
        }

        if (ApiToken::findOne(Yii::$app->getUser()->getId())) {
            return false;
        }

        $t = new ApiToken();
        $t->id = Yii::$app->getUser()->getId();
        $t->token = Yii::$app->getSecurity()->generateRandomString(64);

        $attempts = 0;

        while (!$t->save()) {

            $t->token = Yii::$app->getSecurity()->generateRandomString(64);

            $attempts++;

            if ($attempts > 100) {
                throw new Exception('Cant generate api token!');
            }
        }

        return true;
    }
}
