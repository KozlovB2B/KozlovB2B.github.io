<?php

namespace app\modules\site\models;

use Yii;

/**
 * This is the model class for table "email_services".
 *
 * @property integer $id
 * @property string $domain
 * @property string $name
 * @property string $url
 */
class EmailServices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_services';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain'], 'string', 'max' => 24],
            [['name'], 'string', 'max' => 32],
            [['url'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Domain',
            'name' => 'Name',
            'url' => 'Url',
        ];
    }


    /**
     * Recognize user email service
     *
     * @param string $email
     * @return EmailServices
     */
    public static function recognizeService($email)
    {
        list($user, $domain) = explode('@', $email);

        $service = !empty($domain) ? EmailServices::find()->where('domain=:domain', [':domain' => $domain])->one() : null;

        return $service;
    }
}
