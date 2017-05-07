<?php

namespace app\modules\user\components\mailer;

use Yii;
use yii\base\Component;

/**
 * Class BaseMailer
 *
 * @package app\modules\user\components
 */
class BaseMailer extends Component
{
    /** @var string|array Обратный адрес */
    public $sender;

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $params
     *
     * @return bool
     */
    protected function sendMessage($to, $subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = Yii::$app->params['mails']['no-reply'];
        }

        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
    }
}
