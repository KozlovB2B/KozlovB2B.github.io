<?php

namespace app\modules\user\models;

use Yii;
use app\modules\core\components\ActiveRecord;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "avatar".
 *
 * @property integer $user_id
 * @property string $filename
 *
 * @property User $user
 */
class Avatar extends ActiveRecord
{
    /** @const integer Размер квадрата аватарки в пикселях */
    const AVATAR_SIZE = 200;

    /** @const integer Дефолтная ава */
    const DEFAULT_PNG = 'default.png';

    /** @var Avatar Текущий аватар */
    protected static $_current;


    /**
     * Где хранятся аватарки
     *
     * @return bool|string
     */
    public function getStorageDirectory()
    {
        return Yii::getAlias('@app/public_html/uploads/user-avatar');
    }

    /**
     * @return bool|string
     */
    public function getBaseUrl()
    {
        return Yii::getAlias('@web/uploads/user-avatar');
    }

    /**
     * URL файла аватара
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getBaseUrl() . DIRECTORY_SEPARATOR . $this->filename;
    }

    /**
     * URL файла аватара
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getStorageDirectory() . DIRECTORY_SEPARATOR . $this->filename;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'avatar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['filename'], 'string', 'max' => 128],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    /**
     * Возвращает аватар текущего пользователя
     *
     * @return Avatar
     * @throws ForbiddenHttpException
     */
    public static function current()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            throw new ForbiddenHttpException('Аватары доступны только авторизованным пользователям!');
        }

        if (static::$_current === null) {
            if (Yii::$app->getUser()->identity->avatar) {
                static::$_current = Yii::$app->getUser()->identity->avatar;
            } else {
                $avatar = new Avatar();
                $avatar->user_id = Yii::$app->getUser()->getId();
                $avatar->setDefault();
                $avatar->save(false);

                static::$_current = $avatar;
            }
        }

        return static::$_current;
    }

    /**
     * Устанавливает дефолтный аватарчик
     */
    public function setDefault()
    {
        if (!$this->loadFromGravatar()) {
            $this->filename = Avatar::DEFAULT_PNG;
        }
    }

    /**
     * Пытается скачать картинку с граватара
     *
     * @return bool
     */
    function loadFromGravatar()
    {
        $url = 'http://www.gravatar.com/avatar/' . md5(strtolower(Yii::$app->getUser()->identity->email));
        $response = get_headers($url . "?d=404");

        if ($response[0] != "HTTP/1.0 404 Not Found") {
            $this->filename = uniqid(time()) . ".jpg";
            copy($url . "?s=" . Avatar::AVATAR_SIZE, $this->getPath());

            return true;
        }

        return false;
    }
}
