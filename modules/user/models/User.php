<?php

namespace app\modules\user\models;

use app\modules\integration\modules\amo\components\AmoApi;
use app\modules\integration\modules\amo\components\AmoLead;
use app\modules\integration\modules\amo\models\ApiCredentials;
use app\modules\user\helpers\Password;
use omgdef\unisender\UniSenderWrapper;
use Yii;
use app\modules\user\models\profile\Profile;
use yii\base\Exception;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use app\modules\user\Module;
use yii\behaviors\TimestampBehavior;
use app\modules\core\components\ActiveRecord;
use app\modules\user\models\profile\ProfileRelation;
use DateTimeZone;
use DateTime;
use app\modules\user\models\profile\Head;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $unconfirmed_email
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $blocked_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $creator_id
 * @property integer $confirmed_at
 * @property string $timezone_id
 * @property string $registration_ip
 * @property boolean $v2
 *
 *
 * @property Profile $profile
 * @property User $creator
 * @property Avatar $avatar
 * @property ProfileRelation[] $profileRelations
 * @property ProfileRelation $profileRelation
 * @property ProfileRelation $currentProfileRelation
 */
class User extends ActiveRecord implements IdentityInterface
{
    /** @const Ограничения для логина */
    const USERNAME_MIN_LENGTH = 3;
    const USERNAME_MAX_LENGTH = 32;

    /** @const int Две недели */
    const REMEMBER_FOR = 1209600;

    /** @var string Пароль в исходном виде, используется при создании пользователя */
    public $password;

    /** @var string Регулярка для логина */
    public static $usernameRegexp = '/^[a-zA-Z][a-zA-Z0-9-@_\.]+$/';
    public static $usernameRegexpExplain = 'Имя пользователя должно содержать только латинские буквы и цифры и начинаться с буквы!';

    /** @var string Регулярка для пароля */
    public static $passwordRegexp = '/^(?=.*\d)(?!.*\s).*$/';
    public static $passwordRegexpExplain = 'Пароль должен обязательно иметь в своем составе хотя бы одну цифру.';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfileRelations()
    {
        return $this->hasMany(ProfileRelation::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfileRelation()
    {
        return $this->hasOne(ProfileRelation::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id'])->from('user creator');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvatar()
    {
        return $this->hasOne(Avatar::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentProfileRelation()
    {
        return $this->hasOne(ProfileRelation::className(), ['user_id' => 'id'])->andWhere('is_current = 1');
    }

    /**
     * Возвращает профиль прользователя. Если не указан класс - вернет текущий профиль
     *
     * @param string $profile_class
     * @return Profile|array|null|\yii\db\ActiveRecord
     */
    public function getProfile($profile_class = null)
    {
        if ($profile_class === null) {
            if (!$this->currentProfileRelation || !$this->currentProfileRelation->getProfile()) {
                if (Yii::$app->getUser()->can("user_head_manager") && UserHeadManager::findOne(Yii::$app->getUser()->getId())) {
                    $relation = new ProfileRelation();
                    $relation->is_current = 1;
                    $relation->user_id = Yii::$app->getUser()->getId();
                    $relation->profile_class = 'Head';
                    $relation->save(false);
                    $this->populateRelation('currentProfileRelation', $relation);
                } else {
                    throw new \RuntimeException('У данного пользователя отсутствует текущий профиль. Обратитесь в отдел разработки.');
                }
            }

            return $this->currentProfileRelation->getProfile();
        } else {
            /** @var ProfileRelation $rel */
            $rel = ProfileRelation::find()->where(['user_id' => $this->id, 'profile_class' => $profile_class])->one();

            if (!$rel) {
                throw new \RuntimeException('У данного пользователя отсутствует запрашиваемый профиль. Обратитесь в отдел разработки.');
            }

            return $rel->getProfile();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blocked_at', 'created_at', 'updated_at'], 'integer'],
            ['password_hash', 'string', 'max' => 60],
            [['auth_key', 'timezone_id'], 'string', 'max' => 32],
            ['email', 'string', 'min' => User::USERNAME_MIN_LENGTH, 'max' => User::USERNAME_MAX_LENGTH],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            ['email', 'trim'],
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'trim'],
            ['username', 'string', 'min' => User::USERNAME_MIN_LENGTH, 'max' => User::USERNAME_MAX_LENGTH],
            ['username', 'email'],
            ['password', 'required', 'on' => ['create']],
            ['password', 'string', 'min' => 6, 'on' => ['create']],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creator_id' => 'Создатель',
            'confirmed_at' => 'Подтвержден',
            'username' => 'Имя пользователя',
            'blocked_at' => 'Дата блокировки',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',
            'unconfirmed_email' => 'Новый Email',
            'email' => 'Email',
            'timezone_id' => 'Часовой пояс',
        ];
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        return Yii::$app->getModule('user');
    }


    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }


    /**
     * @return bool Whether the user is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->blocked_at != null;
    }

    /**
     * Сброс пароля
     *
     * @param string $password
     *
     * @return bool
     */
    public function resetPassword($password)
    {
        return (bool)$this->updateAttributes(['password_hash' => Password::hash($password)]);
    }

    /**
     * Блокировка
     */
    public function block()
    {
        return (bool)$this->updateAttributes([
            'blocked_at' => time(),
            'auth_key' => Yii::$app->security->generateRandomString(),
        ]);
    }

    /**
     * Разблокировка
     */
    public function unblock()
    {
        return (bool)$this->updateAttributes(['blocked_at' => null]);
    }


    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('auth_key', Yii::$app->security->generateRandomString());
        }

        if (!empty($this->password)) {
            $this->setAttribute('password_hash', Password::hash($this->password));
        }

        return parent::beforeSave($insert);
    }

    /* Реализация интерфейса IdentityInterface */

    /** @inheritdoc */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /** @inheritdoc */
    public function validateAuthKey($authKey)
    {
        return $this->getAttribute('auth_key') === $authKey;
    }

    /** @inheritdoc */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /** @inheritdoc */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /** @inheritdoc */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }


    /**
     * Создает пользователя. Пользователь в системе может быть создан только через этот метод и никак иначе.
     * Пользователь не может бть создан без профиля (специализации).
     * Процесс создания пользователя обернут в транзакцию бд.
     * Сначала создается корневая запись пользователя, потом попытка записать данные профиля.
     * Если на каком либо этапе что-то пошло не так - транзакция откатывается а метод возвращает false.
     * Если все ок - вернет true
     *
     * @param Profile $profile
     * @return bool
     */
    public function create(Profile $profile)
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Вызов метода "' . __CLASS__ . '::' . __METHOD__ . '" для существующего пользователя');
        }

        $transaction = Yii::$app->getDb()->beginTransaction();

        try {
            if (Yii::$app instanceof \yii\web\Application && Yii::$app->getUser() && !Yii::$app->getUser()->getIsGuest()) {
                $this->creator_id = Yii::$app->getUser()->getId();
            }

            if (!$this->timezone_id) {
                $this->timezone_id = Yii::$app->getTimeZone();
            }

            $this->v2 = 1;

            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            $relation = new ProfileRelation();
            $relation->user_id = $this->id;
            $relation->profile_class = (new \ReflectionClass($profile))->getShortName();

            if (!$relation->save()) {
                $this->addErrors($relation->getErrors());
                $transaction->rollBack();
                return false;
            }

            $profile->user_id = $this->id;

            if (!$profile->create()) {
                $transaction->rollBack();
                return false;
            }

            $role = Yii::$app->getAuthManager()->getRole($profile->getRole());

            if (!$role) {
                throw new \RuntimeException('Не найдена роль ' . $profile->getRole());
            }

            Yii::$app->getAuthManager()->assign($role, $this->id);

            $transaction->commit();

            return true;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            $this->addError('id', $ex->getMessage());
            return false;
        }
    }


    /**
     * @return bool Подтвержден ли профиль
     */
    public function getIsConfirmed()
    {
        return !!$this->confirmed_at;
    }

    /**
     * Confirms the user by setting 'confirmed_at' field to current time.
     */
    public function confirm()
    {
        $confirmed = (bool)$this->updateAttributes(['confirmed_at' => time()]);

        if (YII_ENV_PROD) {
            $this->subscribeToUnisenderLetters();
        }

        return $confirmed;
    }

    /**
     * Пробует подтвердить аккаунт
     *
     * @param string $code Код подтверждения
     * https://scriptdesigner.ru/user/user/confirm?id=5194&code=iPWMQWdN-QukRLI_ihT1j7-5Wlfj5N4W
     * @return boolean
     */
    public function attemptConfirmation($code)
    {
        $token = Token::find()->where(['user_id' => $this->id, 'code' => $code, 'type' => Token::TYPE_CONFIRMATION])->one();

        if ($token instanceof Token && !$token->isExpired) {
            if (($success = $this->confirm())) {
                $token->delete();
                Yii::$app->user->login($this, User::REMEMBER_FOR);
                $message = 'Спасибо! Ваша регистрация завершена!';
            } else {
                $message = 'Ваш аккаунт не был подтвержденн по неизвестной причине!';
                throw new Exception($message);

            }
        } else {
            $success = false;
            $message = 'Ссылка подтверждения недействительно или устарела. Пожалуйста запросите ее повторно.';
            throw new Exception($message);
        }

        Yii::$app->session->setFlash($success ? 'success' : 'danger', $message);

        return $success;
    }

    /**
     * Subscribe user to unisender
     */
    public function subscribeToUnisenderLetters()
    {
        $head = Head::findOne($this->id);

        if (!$head) {
            return false;
        }

        $u = new UniSenderWrapper();
        $u->apiKey = '675c6agysxqpxzp3rc87qernamj9prf7ebqt3zsa';
        $u->timeout = 60;

        $list_ids = \Yii::$app->params['unisender_list'];
        $fields = ["email" => $this->email, "phone" => $head->phone, "Name" => !empty($head->first_name) ? $head->first_name : $this->email];
        $ip = !empty($head->user->registration_ip) ? $head->user->registration_ip : Yii::$app->getRequest()->getUserIP();
        $params = ["double_optin" => 3, "request_ip" => $ip, "confirm_ip" => $ip];
        $params['list_ids'] = $list_ids;
        $params['fields'] = $fields;
        $u->subscribe($params);

        return true;
    }



    /**
     * @return \yii\web\User
     */
    public static function current()
    {
        return Yii::$app->getUser();
    }

    /**
     * @return \app\modules\user\models\User
     */
    public static function identity()
    {
        return Yii::$app->getUser()->getIdentity();
    }

    /**
     * @return array
     */
    public static function timeZonesList()
    {
        $result = [];

        foreach (DateTimeZone::listIdentifiers() as $el) {
            $result[$el] = $el;
        }

        return $result;
    }

    /**
     * Конвертирует дату в объект DateTime с учетом временной зоны пользователя.
     * Если не передан пользователь - использует пользователя текущей сессии.
     *
     * @param string $date
     * @param User|null $user
     * @return DateTime
     */
    public static function getDateTime($date, User $user = null)
    {
        if (!$user) {
            $user = User::current();
        }

        if (is_int($date)) {
            $datetime = new DateTime(null, new DateTimeZone($user->timezone_id));
            $datetime->setTimestamp($date);
        } else {
            $datetime = new DateTime($date, new DateTimeZone($user->timezone_id));
        }

        return $datetime;
    }

    /**
     * Конвертирует дату в объект DateTime с учетом временной зоны текущего объекта пользователя.
     *
     * @param string $date
     * @return DateTime
     */
    public function dateTime($date)
    {
        return self::getDateTime($date, $this);
    }
}
