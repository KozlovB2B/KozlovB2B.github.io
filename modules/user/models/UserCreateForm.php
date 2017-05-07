<?php
namespace app\modules\user\models;

use app\modules\user\components\mailer\UserMailer;
use app\modules\user\helpers\Password;
use app\modules\user\models\profile\ProfileRelation;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use app\modules\user\models\profile\Profile;

/**
 * Class OperatorRegistrationForm Форма регистрации оператора
 *
 * @package app\modules\user\models
 */
class UserCreateForm extends Model
{
    /**
     * @var string Логин должен быть не более 15 знаков.
     * Если данный логин занят, то система не дает заполнить следующее поле и данное поле выделяется цветом,
     * появляется надпись “Данный логин уже используется”
     */
    public $username;

    /**
     * @var string Пароль должен содержать и буквы, и цифры
     */
    public $password;

    /**
     * @var string Email
     */
    public $email;

    /**
     * @var User
     */
    public $user;

    /**
     * @var string Профиль
     */
    public $profile = ProfileRelation::PROFILE_CLASS_OPERATOR;

    /** @var Profile */
    protected $_profile_model;

    /**
     * Экземпляр модели профиля выбранного в форме
     *
     * @return Profile|object
     * @throws \yii\base\InvalidConfigException
     */
    public function getProfileModel()
    {
        if ($this->_profile_model === null) {
            if ($this->user && !$this->user->getIsNewRecord()) {
                $this->_profile_model = $this->user->getProfile();
            } else {
                $this->_profile_model = Yii::createObject(ProfileRelation::profileClassFullName($this->profile));
            }
        }

        return $this->_profile_model;
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $this->_profile_model = null;

        $data_loaded = parent::load($data, $formName);

        return $data_loaded && $this->getProfileModel()->load($data);
    }

    /**
     * @return array Список сценариев
     */
    public static function scenariosList()
    {
        return [
            'create' => 'Создать и подтвердить',
            'invite' => 'Выслать приграсительное присьмо',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['email', 'profile'], 'required', 'on' => ['create', 'invite']],
            [['username', 'password'], 'required', 'on' => 'create'],
            [['email', 'username'], 'string', 'min' => User::USERNAME_MIN_LENGTH, 'max' => User::USERNAME_MAX_LENGTH],
            ['username', 'match', 'pattern' => User::$usernameRegexp, 'message' => User::$usernameRegexpExplain],
            [
                'username',
                'unique',
                'targetClass' => User::className(),
                'message' => 'Такой логин уже используется!'
            ],
            [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message' => 'Такой email уже используется!'
            ],
            ['email', 'email'],
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            ['profile', 'in', 'range' => array_keys(ProfileRelation::profilesForCreatingForm())],
            ['password', 'string', 'min' => 6],
            ['password', 'match', 'pattern' => User::$passwordRegexp, 'message' => User::$passwordRegexpExplain],
            ['password', 'required', 'on' => 'create']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'profile' => 'Профиль'
        ];
    }


    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'user___user___create_form';
    }

    /**
     * Создает нового пользователя с заданным паролем, логином и email
     * сразу помечает как подтвержденный.
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function create()
    {
        $this->user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'create',
            'username' => $this->username,
            'password' => $this->password,
            'email' => $this->email,
            'confirmed_at' => time()
        ]);

        if (!$this->user->create($this->getProfileModel())) {
            $this->addError('username', 'Не удалось создать пользователя: ' . strip_tags(Html::errorSummary([$this->user, $this->getProfileModel()], ['header' => false])));

            return false;
        }

        return true;
    }

    /**
     * Регистрирует пользователя и высылает пригласительное письмо
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function invite()
    {
        $this->user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'create',
            'username' => $this->email,
            'password' => Password::generate(8),
            'email' => $this->email
        ]);

        if (!$this->user->create($this->getProfileModel())) {
            $this->addError('username', 'Не удалось пригласить пользователя: ' . strip_tags(Html::errorSummary([$this->user, $this->getProfileModel()], ['header' => false])));

            return false;
        }

        /** @var Token $token */
        $token = Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_INVITE]);
        $token->link('user', $this->user);
        (new UserMailer())->sendInviteMessage($this->user, $token);

        return true;
    }

    /**
     * Создает пользователя
     *
     * @return bool
     */
    public function performScenario()
    {
        $this->getProfileModel()->setScenario($this->scenario);

        if (!$this->validate() || !$this->getProfileModel()->validate()) {
            return false;
        }

        switch ($this->scenario) {
            case 'create' :
                $result = $this->create();

                break;
            case 'invite' :
                $result = $this->invite();

                break;
            default:
                $result = false;

                break;
        }

        return $result;
    }
}
