<?php

namespace app\modules\integration\modules\zebra\models;

use app\modules\integration\modules\zebra\components\ZebraApi;
use Yii;
use yii\db\ActiveRecord;
use app\modules\integration\models\EnabledList;

/**
 * This is the model class for table "zebra_api_credentials".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $is_active
 * @property string $login
 * @property string $password
 * @property string $realm
 */
class ApiCredentials extends ActiveRecord
{
    /**
     * @var ZebraApi
     */
    public $api;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zebra_api_credentials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'is_active'], 'integer'],
            [['login', 'realm', 'password'], 'string', 'max' => 128],
            [['login', 'realm', 'password'], 'required'],
            [['password'], 'unique'],
            [['password'], 'auth']
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
        EnabledList::enable($this->user_id, 'zebra');
    }

    /**
     *
     */
    public function disableModule()
    {
        EnabledList::disable($this->user_id, 'zebra');

    }

    /**
     * Get model by password
     *
     * @param string $key
     * @return ApiCredentials
     */
    public static function getByKey($key)
    {
        return ApiCredentials::find()->where('{{password}}=:k', ['k' => $key])->one();
    }

    /**
     * Авторизуеся в REST API Zebra
     *
     * @return bool
     */
    public function auth()
    {

        try {
            $this->api = new ZebraApi($this);
        } catch (\Exception $ex) {
            $this->addError('password', Yii::t('zebra', 'Auth error! Check your credentials is right.'));
            return false;
        }
        return true;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        Yii::$app->getModule('integration')->getModule('zebra');

        return [
            'id' => Yii::t('zebra', 'ID'),
            'user_id' => Yii::t('zebra', 'User ID'),
            'created_at' => Yii::t('zebra', 'Created At'),
            'login' => Yii::t('zebra', 'Login'),
            'realm' => Yii::t('zebra', 'Realm'),
            'password' => Yii::t('zebra', 'Password')
        ];
    }
}
