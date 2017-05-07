<?php

namespace app\modules\integration\modules\amo\models;

use app\modules\integration\modules\amo\components\AmoApi;
use app\modules\script\models\ApiToken;
use Yii;


/**
 * This is the model class for table "amo_api_credentials".
 * select id, user_id, created_at, is_active, user, domain, key from amo_api_credentials;
 * @property integer $id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $is_active
 * @property string $user
 * @property string $domain
 * @property string $key
 * @property string $config
 * @property string $cookie
 */
class ApiCredentials extends \yii\db\ActiveRecord
{
    /**
     * @var AmoApi
     */
    public $api;

    public $token;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'amo_api_credentials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'is_active'], 'integer'],
            [['config', 'cookie'], 'string'],
            [['user', 'domain', 'key'], 'string', 'max' => 128],
            [['key'], 'unique', 'on' => 'create'],
            [['user'], 'auth', 'on' => 'create']
        ];
    }

    /**
     * Get model by key
     *
     * @param string $key
     * @return ApiCredentials
     */
    public static function getByKey($key)
    {
        return ApiCredentials::find()->where('{{key}}=:k', ['k' => $key])->one();
    }

    /**
     * Авторизуеся в REST API Amo CRM
     *
     * @return bool
     */
    public function auth()
    {
        try {
            $this->api = new AmoApi($this);
        } catch (\Exception $ex) {
            $this->addError('user', Yii::t('amo', 'Auth error! Check your credentials is right.'));
            return false;
        }

        return true;
    }

    /**
     *
     */
    public function fillFieldsUsingUrl($url){

    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        Yii::$app->getModule('integration')->getModule('amo');

        return [
            'id' => Yii::t('amo', 'ID'),
            'user_id' => Yii::t('amo', 'User ID'),
            'created_at' => Yii::t('amo', 'Created At'),
            'user' => Yii::t('amo', 'User'),
            'domain' => Yii::t('amo', 'Domain'),
            'key' => Yii::t('amo', 'Api key'),
            'config' => Yii::t('amo', 'Config'),
            'cookie' => Yii::t('amo', 'Cookie'),
        ];
    }


    /**
     * Adding api credential
     *
     * @return bool
     */
    public function create()
    {
        $this->setScenario('create');

        if (!$this->validate()) {
            return false;
        }

        $this->user_id = Yii::$app->getUser()->getId();
        $this->created_at = time();
        $this->is_active = 1;

        return $this->save();
    }
}
