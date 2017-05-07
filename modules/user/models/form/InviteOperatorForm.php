<?php

namespace app\modules\user\models\form;

use app\modules\user\components\mailer\UserMailer;
use app\modules\user\helpers\Password;
use app\modules\user\models\profile\Operator;
use app\modules\user\models\Token;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Class OperatorRegistrationForm Форма регистрации студента
 *
 * @package app\modules\user\models
 */
class InviteOperatorForm extends Model
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
     * @var Operator
     */
    public $operator;

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

        /** @var Operator $operator */
        $operator = Yii::createObject([
            'class' => Operator::className(),
            'scenario' => 'invite',
            'head_id' => Yii::$app->getUser()->getId()
        ]);

        parent::__construct([
            'email' => $user->email,
            'user' => $user,
            'operator' => $operator
        ]);
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $data_loaded = parent::load($data, $formName);
        $operator_loaded = $this->operator->load($data);

        return $data_loaded && $operator_loaded;
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
        if (!$this->validate() || !$this->operator->validate()) {
            return false;
        }

        $this->user->username = $this->user->email = $this->email;

        if (!$this->user->create($this->operator)) {
            $this->addError('username', 'Не удалось пригласить оператора: ' . strip_tags(Html::errorSummary([$this->user, $this->operator], ['header' => false])));

            return false;
        }

        /** @var Token $token */
        $token = Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_INVITE]);
        $token->link('user', $this->user);
        (new UserMailer())->sendInviteMessage($this->user, $token);

        return true;
    }
}
