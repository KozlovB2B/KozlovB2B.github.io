<?php

namespace app\modules\user\components\mailer;

use app\modules\user\models\User;
use app\modules\user\models\Token;
use Yii;

/**
 * Class OperatorMailer
 *
 * @package app\modules\user\components
 */
class OperatorMailer extends BaseMailer
{
    /** @var string */
    public $viewPath = '@app/modules/user/views/operator/mail';

    /**
     * Сообщение новому оператору после регистрации
     *
     * @param User $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendWelcomeMessage(User $user, Token $token)
    {
        $this->sender = Yii::$app->params['mails']['welcome'];
        return $this->sendMessage($user->email, 'Добро пожаловать на борт!', 'welcome', ['user' => $user, 'token' => $token]);
    }
}
