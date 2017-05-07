<?php

namespace app\modules\user\models;


use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * Token Active Record model.
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $created_at
 * @property integer $type
 * @property string $url
 * @property bool $isExpired
 * @property User $user
 */
class Token extends ActiveRecord
{
    /** @const int Срок кодности токена */
    const TTL = 8640000;

    /** @const int Тип токена "Подтверждение аккаунта оператора" */
    const TYPE_CONFIRMATION = 0;

    /** @const int Тип токена "Восстановление пароля оператора" */
    const TYPE_PASSWORD_RECOVERY = 1;

    /** @const int Тип токена "Ссылка для пригласительного письма" */
    const TYPE_INVITE = 2;

    /** @inheritdoc */
    public static function tableName()
    {
        return 'token';
    }

    /** @inheritdoc */
    public static function primaryKey()
    {
        return ['user_id', 'code', 'type'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Ссылка где можно использовать токен
     *
     * @return string
     */
    public function getUrl()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
                $route = '/user/user/confirm';
                break;
            case self::TYPE_PASSWORD_RECOVERY:
                $route = '/user/password-recovery/reset';
                break;
            case self::TYPE_INVITE:
                $route = '/user/user/accept-invite';
                break;
            default:
                throw new \RuntimeException();
        }

        return Url::to([$route, 'id' => $this->user_id, 'code' => $this->code], true);
    }

    /**
     * @return bool Whether token has expired.
     */
    public function getIsExpired()
    {
        return ($this->created_at + Token::TTL) < time();
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            static::deleteAll(['user_id' => $this->user_id, 'type' => $this->type]);
            $this->setAttribute('created_at', time());
            $this->setAttribute('code', Yii::$app->security->generateRandomString());
        }

        return parent::beforeSave($insert);
    }
}
