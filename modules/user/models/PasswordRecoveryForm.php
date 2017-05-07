<?php

namespace app\modules\user\models;

use app\modules\user\components\mailer\UserMailer;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class PasswordRecoveryForm
 *
 * Форма восстановления пароля
 *
 * @package app\modules\user\models
 */
class PasswordRecoveryForm extends Model
{

    /** @var string */
    public $username_or_email;

    /** @var string Новый пароль */
    public $new_password;

    /** @var string Повторить пароль */
    public $new_password_repeat;

    /** @var User */
    protected $user;

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'username_or_email' => 'Логин или email',
            'new_password' => 'Ваш новый пароль',
            'new_password_repeat' => 'Повторите пароль',
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        return ArrayHelper::merge($scenarios, [
            'request' => ['username_or_email'],
            'reset' => ['new_password', 'new_password_repeat'],
        ]);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['username_or_email', 'filter', 'filter' => 'trim', 'on' => 'request'],
            ['username_or_email', 'required', 'on' => 'request'],
            [['new_password', 'new_password_repeat'], 'required', 'on' => 'reset'],
            [
                'username_or_email',
                function ($attribute) {

                    $this->user = User::find()->byUsernameOrEmail($this->username_or_email)->one();

                    if ($this->user == null) {
                        $this->addError($attribute, 'Пользователь с таким логином или email не найден!');

                        return false;
                    }

                    if (!$this->user->getIsConfirmed()) {
                        $this->addError($attribute, 'Вы не подтвердили свой email!');

                        return false;
                    }

                    if ($this->user->getIsBlocked()) {
                        $this->addError($attribute, 'Ваш аккаунт заблокирован!');

                        return false;
                    }

                    return true;
                }
            ],
            ['new_password', 'string', 'min' => 6, 'on' => 'reset'],
            ['new_password', 'match', 'pattern' => User::$passwordRegexp, 'message' => User::$passwordRegexpExplain, 'on' => 'reset'],
            ['new_password_repeat', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли должны совпадать!', 'on' => 'reset'],
        ];
    }

    /**
     * Посылает сообщение с инструкцией
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function sendRecoveryMessage()
    {
        if ($this->validate()) {

            /** @var Token $token */
            $token = Yii::createObject([
                'class' => Token::className(),
                'user_id' => $this->user->id,
                'type' => Token::TYPE_PASSWORD_RECOVERY,
            ]);

            if (!$token->save(false)) {
                $this->addError('username_or_email', 'Не удалось создать ссылку для смены пароля. Попробуйте позже.');

                return false;
            }

            if (!(new UserMailer())->sendRecoveryMessage($this->user, $token)) {
                $this->addError('username_or_email', 'При отправке почты возникла ошибка.');

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Сбрасывает пароль
     *
     * @param Token $token
     * @return bool
     * @throws \Exception
     */
    public function resetPassword(Token $token)
    {
        if (!$this->validate() || $token->user === null) {
            return false;
        }

        if ($token->user->resetPassword($this->new_password)) {
            $token->delete();
        } else {
            $this->addError('new_password', 'При смене пароля произошла ошибка. Пожалуйста попробуйте еще раз позже');

            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'user___user__password_recovery_form';
    }
}
