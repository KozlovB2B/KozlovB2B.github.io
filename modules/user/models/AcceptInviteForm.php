<?php
namespace app\modules\user\models;

use app\modules\user\models\profile\Profile;
use Yii;
use yii\base\InvalidCallException;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Class AcceptInviteForm Форма завершения регистрации по приглашению
 *
 * @package app\modules\user\models
 */
class AcceptInviteForm extends Model
{
    /**
     * @var string Логин должен быть не более 15 знаков.
     * Если данный логин занят, то система не дает заполнить следующее поле и данное поле выделяется цветом,
     * появляется надпись “Данный логин уже используется”
     */
    public $username;

    /** @var string Пароль должен содержать и буквы, и цифры */
    public $password;

    /** @var string Повторить пароль */
    public $password_repeat;

    /** @var string Идентификатор часового пояса пользователя */
    public $timezone_id;

    /** @var User */
    public $user;

//    /** @inheritdoc */
//    public function load($data, $formName = null)
//    {
//        $data_loaded = parent::load($data, $formName);
//
//        return $data_loaded && $this->user->profile->load($data);
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'password_repeat', 'timezone_id'], 'required'],
            ['username', 'string', 'min' => User::USERNAME_MIN_LENGTH, 'max' => User::USERNAME_MAX_LENGTH],
            ['username', 'match', 'pattern' => User::$usernameRegexp, 'message' => User::$usernameRegexpExplain],
            [
                'username',
                function () {
                    if (User::find()->byUsername($this->username)->andWhere('id != :id', [':id' => $this->user->id])->exists()) {
                        $this->addError('username', 'Такое имя пользователя уже используется!');
                    }
                }
            ],
            ['password', 'string', 'min' => 6],
            ['timezone_id', 'string', 'max' => 32],
            ['password', 'match', 'pattern' => User::$passwordRegexp, 'message' => User::$passwordRegexpExplain],
            ['password_repeat', 'compare', 'compareAttribute' => 'password']
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
            'timezone_id' => 'Часовой пояс',
            'password_repeat' => 'Повторите пароль'
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'user___user__accept_invite_form';
    }

    /**
     * Выполнение функции формы.
     *
     * Завершает регистрацию пользователя, вносит изменения в профиль
     *
     * @return bool
     */
    public function accept()
    {
        if (!$this->user) {
            throw new InvalidCallException('Подтвердить можно только существующего пользователя!');
        }

        $this->user->profile->setScenario('accept-invite');

        if (!$this->validate() || !$this->user->profile->validate()) {
            return false;
        }

        $this->user->confirmed_at = time();
        $this->user->username = $this->username;
        $this->user->password = $this->password;
        $this->user->timezone_id = $this->timezone_id;

        if (!$this->user->save()) {
            $this->addError('username', Html::errorSummary($this->user, ['header' => false]));

            return false;
        }

        if (!$this->user->profile->save()) {
            $this->addError('username', Html::errorSummary($this->user->profile, ['header' => false]));

            return false;
        }

        $this->user->profile->trigger(Profile::AFTER_ACCEPT_INVITE);

        Yii::$app->getUser()->login($this->user, User::REMEMBER_FOR);

        return true;
    }
}
