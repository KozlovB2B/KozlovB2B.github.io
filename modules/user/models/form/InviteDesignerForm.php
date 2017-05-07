<?php

namespace app\modules\user\models\form;

use app\modules\user\components\mailer\UserMailer;
use app\modules\user\helpers\Password;
use app\modules\user\models\profile\Designer;
use app\modules\user\models\Token;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Class DesignerRegistrationForm Форма регистрации студента
 *
 * @package app\modules\user\models
 */
class InviteDesignerForm extends Model
{
    /**
     * @var string Email
     */
    public $email;

    /**
     * @var User
     */
    public $user;

    /**
     * @var Designer
     */
    public $designer;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct()
    {
        /** @var User $user */
        $user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'create',
            'username' => $this->email,
            'v2' => 1,
            'password' => Password::generate(8),
            'email' => $this->email
        ]);

        /** @var Designer $designer */
        $designer = Yii::createObject([
            'class' => Designer::className(),
            'scenario' => 'invite',
            'head_id' => Yii::$app->getUser()->getId()
        ]);

        parent::__construct([
            'email' => $user->email,
            'user' => $user,
            'designer' => $designer
        ]);
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $data_loaded = parent::load($data, $formName);
        $designer_loaded = $this->designer->load($data);

        return $data_loaded && $designer_loaded;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['email'], 'required'],
            [['email'], 'string', 'min' => User::USERNAME_MIN_LENGTH, 'max' => User::USERNAME_MAX_LENGTH],
            [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message' => 'Такой email уже используется!'
            ],
            ['email', 'email'],
            ['email', 'filter', 'filter' => 'trim']
        ];
    }

    /**
     * Регистрирует оператора и высылает пригласительное письмо
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function invite()
    {
        if (!$this->validate() || !$this->designer->validate()) {
            return false;
        }

        $this->user->username = $this->user->email = $this->email;

        if (!$this->user->create($this->designer)) {
            $this->addError('username', 'Не удалось пригласить проектировщика: ' . strip_tags(Html::errorSummary([$this->user, $this->designer], ['header' => false])));

            return false;
        }

        /** @var Token $token */
        $token = Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_INVITE]);
        $token->link('user', $this->user);
        (new UserMailer())->sendInviteMessage($this->user, $token);

        return true;
    }
}
