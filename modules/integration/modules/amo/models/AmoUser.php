<?php

namespace app\modules\integration\modules\amo\models;

use app\modules\integration\modules\amo\components\AmoApi;
use app\modules\user\models\profile\Operator;
use app\modules\user\models\User;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\modules\integration\models\EnabledList;

/**
 * This is the model class for table "integration_amo_user".
 *
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $head_id
 * @property string $amouser
 * @property string $amohash
 * @property string $subdomain
 *
 * @property User $user
 * @property UserHeadManager $head
 */
class AmoUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false
            ]
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
        if ($this->head_id == $this->user_id) {
            EnabledList::enable($this->head_id, 'amo');
        }
    }

    /**
     *
     */
    public function disableModule()
    {
        if ($this->head_id == $this->user_id) {
            EnabledList::disable($this->head_id, 'amo');
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'integration_amo_user';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHead()
    {
        return $this->hasOne(UserHeadManager::className(), ['id' => 'head_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(Operator::className(), ['user_id' => 'user_id']);
    }

    /**
     * @param $user_id
     * @param $amouser
     * @param $amohash
     * @param $subdomain
     * @return AmoUser
     * @throws Exception
     */
    public static function add($user_id, $amouser, $amohash, $subdomain)
    {
        $head = UserHeadManager::findHeadManagerByUser($user_id);

        $model = new AmoUser();
        $model->user_id = $user_id;
        $model->head_id = $head->id;
        $model->amouser = $amouser;
        $model->amohash = $amohash;
        $model->subdomain = $subdomain;
        if (!$model->save()) {
            throw new Exception(implode(',', $model->getFirstErrors()));
        }

        return $model;
    }


    /**
     * @param $amouser
     * @param $amohash
     * @param $subdomain
     * @return bool
     * @throws Exception
     */
    public function change($amouser, $amohash, $subdomain)
    {
        $this->amouser = $amouser;
        $this->amohash = $amohash;
        $this->subdomain = $subdomain;
        if (!$this->save()) {
            throw new Exception(implode(',', $this->getFirstErrors()));
        }
        return true;
    }

    /**
     * Получает файл кукисов
     *
     * @return string
     * @throws Exception
     */
    public function cookieFile()
    {
        if (!$this->user_id) {
            throw new Exception('AmoUser must have user_id for getting cookieFilename');
        }

        $storage = Yii::getAlias('@runtime/amo_session');

        if (!is_dir($storage)) {
            mkdir($storage);
        }

        $filename = $storage . '/' . $this->user_id;

        if (!file_exists($filename)) {
            file_put_contents($filename, '');
        }

        return $filename;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'head_id'], 'integer'],
            [['amouser', 'amohash', 'subdomain'], 'string', 'max' => 128],
            [['amouser'], function () {
                try {
                    (new AmoApi($this))->auth();
                } catch (\Exception $e) {
                    $this->addError('amouser', Yii::t('amo', 'Auth error! Check your credentials is right.'));
                    return false;
                }

                return true;
            }],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['head_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserHeadManager::className(), 'targetAttribute' => ['head_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     * @return AmoUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AmoUserQuery(get_called_class());
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
            'amouser' => Yii::t('amo', 'User'),
            'subdomain' => Yii::t('amo', 'Domain'),
            'amohash' => Yii::t('amo', 'Api key')
        ];
    }
}
