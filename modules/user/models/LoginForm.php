<?php
namespace app\modules\user\models;

use app\modules\user\helpers\Password;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 *
 * Форма авторизации.
 *
 * Получает логин пользователя и пароль, проверяет их и логинит пользоваетеля если все ок.
 * Если пользователь был заблокирован - показывает ошибку в форме.
 *
 * @package app\modules\user\models
 */
class LoginForm extends Model
{
    /** @var string Логин пользователя */
    public $login;

    /** @var string Пароль пользователя */
    public $password;

    /** @var User */
    protected $user;


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль'
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'requiredFields' => [['login', 'password'], 'required'],
            'loginTrim' => ['login', 'trim'],
            'passwordValidate' => [
                'password',
                function ($attribute) {
                    if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                        $this->addError($attribute, 'Неверный логин или пароль!');
                    }
                }
            ],
            'confirmationValidate' => [
                'login',
                function ($attribute) {
                    if ($this->user !== null) {
                        if (!$this->user->getIsConfirmed()) {
                            $this->addError($attribute, 'Вам необходимо подтвердить свой аккаунт!');
                        }
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, 'Ваш аккаунт заблокирован!');
                        }
                    }
                }
            ]
        ];
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->getUser()->login($this->user, User::REMEMBER_FOR);
        } else {
            return false;
        }
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'user___user__login_form';
    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = User::find()->byUsername($this->login)->one();

            return true;
        } else {
            return false;
        }
    }
}
