<?php

namespace app\modules\script\models;

use Yii;
use app\modules\user\models\User;


/**
 * This is the model class for table "SipAccount".
 *
 * @property integer $id
 * @property integer $account_id
 * @property string $display_name
 * @property string $private_identity
 * @property string $public_identity
 * @property string $password
 * @property string $realm
 *
 * @property User $user
 */
class SipAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SipAccount';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name', 'private_identity', 'public_identity'], 'string', 'max' => 64],
//            [['private_identity',  'realm'], 'required'],
            [['password', 'realm'], 'string', 'max' => 128],
        ];
    }

    public function beforeSave($insert)
    {
        $this->writePublicIdentity();

        return parent::beforeSave($insert);
    }

    /**
     *
     */
    public function writePublicIdentity()
    {
        if ($this->private_identity && $this->realm) {
            $this->public_identity = $this->private_identity . '@' . $this->realm;

        } else {
            $this->public_identity = null;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'display_name' => Yii::t('script', 'Display Name'),
            'private_identity' => Yii::t('script', 'Private Identity'),
            'public_identity' => Yii::t('script', 'Public Identity'),
            'password' => Yii::t('script', 'Password'),
            'realm' => Yii::t('script', 'Realm'),
        ];
    }

    /**
     * Find or creates sip account object
     *
     * @param int $id
     * @return SipAccount
     * @throws \yii\base\InvalidConfigException
     */
    public static function findOrCreate($id)
    {
        $model = SipAccount::findOne($id);

        if ($model) {
            return $model;
        }

        return Yii::createObject([
            'class' => SipAccount::className(),
            'id' => $id,
        ]);
    }

    /**
     * @inheritdoc
     * @return SipAccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SipAccountQuery(get_called_class());
    }
}
