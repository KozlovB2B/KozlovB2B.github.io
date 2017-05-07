<?php

namespace app\modules\integration\modules\onlinepbx\models;

use app\modules\integration\modules\onlinepbx\components\OnlinepbxApi;
use Yii;
use app\modules\integration\models\EnabledList;

/**
 * This is the model class for table "onlinepbx_api_credentials".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $is_active
 * @property string $phone
 * @property string $key
 * @property string $domain
 */
class ApiCredentials extends \yii\db\ActiveRecord
{
    /**
     * @var OnlinepbxApi
     */
    public $api;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'onlinepbx_api_credentials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'is_active'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['key'], 'string', 'max' => 128],
            [['domain'], 'string', 'max' => 64],
            [['key'], 'unique'],
            [['key'], 'auth']
        ];
    }

    /**
     *
     */
    public function init()
    {
        $this->on(static::EVENT_AFTER_INSERT, function () {
            $this->enableModule();
        });

        $this->on(static::EVENT_AFTER_UPDATE, function () {
            $this->enableModule();
        });

        $this->on(static::EVENT_AFTER_DELETE, function () {
            $this->disableModule();
        });

        parent::init();
    }

    /**
     *
     */
    public function enableModule()
    {
        EnabledList::enable($this->user_id, 'onlinepbx');
    }

    /**
     *
     */
    public function disableModule()
    {
        EnabledList::disable($this->user_id, 'onlinepbx');

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
     * Авторизуеся в REST API Online PBX
     *
     * @return bool
     */
    public function auth()
    {
        try {
            $this->api = new OnlinepbxApi($this);
        } catch (\Exception $ex) {
            $this->addError('user', Yii::t('onlinepbx', 'Auth error! Check your credentials is right.'));
            return false;
        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        Yii::$app->getModule('integration')->getModule('onlinepbx');

        return [
            'id' => Yii::t('onlinepbx', 'ID'),
            'user_id' => Yii::t('onlinepbx', 'User ID'),
            'created_at' => Yii::t('onlinepbx', 'Created At'),
            'phone' => Yii::t('onlinepbx', 'Phone'),
            'domain' => Yii::t('onlinepbx', 'Domain'),
            'key' => Yii::t('onlinepbx', 'Api key')
        ];
    }
}
