<?php

namespace app\modules\user\models;

use app\modules\user\components\Mailer;
use app\modules\user\models\profile\Operator;
use Yii;
use yii\base\Model;

/**
 * Форма повторной отправки кода подтверждения аккаунта оператора на E-mail.
 * Проверяет не подтвердил ли оператор уже свой аккаунт и выдает ошибку если подтвердил.
 *
 * @property Operator $operator
 */
class OperatorEmailConfirmationResendForm extends Model
{

    /** @var string */
    public $email;

    /** @var Operator */
    private $_operator;

    /** @var Mailer */
    protected $mailer;

    /**
     * @param Mailer $mailer
     * @param array $config
     */
    public function __construct(Mailer $mailer, $config = [])
    {
        $this->mailer = $mailer;
        parent::__construct($config);
    }

    /**
     * @return Operator
     */
    public function getOperator()
    {
        if ($this->_operator === null) {
            $this->_operator = Operator::find()->where(['email' => $this->email])->one();
        }

        return $this->_operator;
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailExist' => ['email', 'exist', 'targetClass' => Operator::className()],
            'emailConfirmed' => [
                'email',
                function () {
                    if ($this->operator != null && $this->operator->getIsConfirmed()) {
                        $this->addError('email', 'Этот аккаунт уже подтвержден!');
                    }
                }
            ],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'user___operator___register_confirmation_resend_form';
    }

    /**
     * Creates new confirmation token and sends it to the user.
     *
     * @return bool
     */
    public function resend()
    {
        if (!$this->validate()) {
            return false;
        }
        /** @var Token $token */
        $token = Yii::createObject([
            'class' => Token::className(),
            'user_id' => $this->operator->user_id,
            'type' => Token::TYPE_CONFIRMATION,
        ]);
        $token->save(false);
        $this->mailer->sendConfirmationMessage($this->operator, $token);
        Yii::$app->session->setFlash('info', Yii::t('user', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'));

        return true;
    }
}
