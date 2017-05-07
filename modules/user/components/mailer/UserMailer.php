<?php

namespace app\modules\user\components\mailer;


use app\modules\user\models\User;
use app\modules\user\models\Token;
use Yii;

/**
 * Class UserMailer
 *
 * @package app\modules\user\components
 */
class UserMailer extends BaseMailer
{
    /** @var string */
    public $viewPath = '@app/modules/user/views/user/mail';

    /**
     * Высылает пригласительное письмо со ссылкой на форму
     * где пользователь может подтвердить свой аккаунт и завершить регистрацию
     *
     * @param User $user
     * @param Token $token
     * @return bool
     */
    public function sendInviteMessage(User $user, Token $token)
    {
        $this->sender = Yii::$app->params['mails']['welcome'];
        return $this->sendMessage($user->email, 'Добро пожаловать!', 'invite', ['user' => $user, 'token' => $token]);
    }

    /**
     * Высылает сообщение со ссылкой для восстановления пароля
     *
     * @param User $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendRecoveryMessage(User $user, Token $token)
    {
        return $this->sendMessage($user->email, 'Восстановление пароля на ' . Yii::$app->name, 'recovery', ['user' => $user, 'token' => $token]);
    }

    /**
     * Sends an email to a user with reconfirmation link.
     *
     * @param User $user
     * @param Token $token
     *
     * @return bool
     */
//    public function sendReconfirmationMessage(User $user, Token $token)
//    {
//        if ($token->type == Token::TYPE_CONFIRM_NEW_EMAIL) {
//            $email = $user->unconfirmed_email;
//        } else {
//            $email = $user->email;
//        }
//
//        return $this->sendMessage($email,
//            'Подтвердите смену почтового ящика на ' . Yii::$app->name,
//            'reconfirmation',
//            ['user' => $user, 'token' => $token]
//        );
//    }


}
