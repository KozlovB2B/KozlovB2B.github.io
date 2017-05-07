<?php
namespace app\modules\user\models;

use app\modules\user\components\AmoLeadCreator;
use Yii;
use yii\base\Model;
use app\modules\user\models\profile\Head;
use yii\helpers\Html;
use app\modules\user\components\mailer\HeadMailer;
use app\modules\aff\models\Account as AffAccount;
use app\modules\billing\models\Account as BillingAccount;
use app\modules\user\helpers\Password;


/**
 * Class HeadRegistrationForm Форма регистрации оператора
 *
 * @package app\modules\user\models
 */
class HeadRegistrationForm extends Model
{
    /**
     * @var string Логин должен быть не более 15 знаков.
     * Если данный логин занят, то система не дает заполнить следующее поле и данное поле выделяется цветом,
     * появляется надпись “Данный логин уже используется”
     */
    public $first_name;

    /**
     * @var string Пароль должен содержать и буквы, и цифры
     */
    public $password;
    public $password_repeat;

    /** @var string phone */
    public $phone;

    /** @var string Почта */
    public $email;

    /**
     * @var User
     */
    public $user;

    /**
     * @var string Я ознакомился полностью и принимаю правила “Пользовательского соглашения по работе с системой”, [активная ссылка] {чекбокс}.
     * При переходе на документ с правилами, пользователь попадает на отдельную сверстанную страницу, а не на прикрепленный .pdf файл, данная страница не должна открываться в новом окне
     */
    public $accept_terms;


    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            'requiredFields' => [['first_name', 'email', 'phone'], 'required'],
            'usernameLength' => ['email', 'string', 'min' => User::USERNAME_MIN_LENGTH, 'max' => User::USERNAME_MAX_LENGTH],
            'trimFields' => [['first_name', 'email', 'phone'], 'filter', 'filter' => 'trim'],
            'emailPattern' => ['email', 'email'],
            'emailUnique' => [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message' => 'Такой Email уже используется!'
            ],
//            'skypeUnique' => [
//                'phone',
//                'unique',
//                'targetClass' => Head::className(),
//                'message' => 'Такой телефон уже используется!'
//            ],
            'acceptTerms' => ['accept_terms', 'required', 'requiredValue' => 1, 'message' => 'Пожалуйста прочтите и примите условия!'],
//            'passwordRequired' => ['password', 'required', 'skipOnEmpty' => false],
//            'passwordLength' => ['password', 'string', 'min' => 6],
//            'passwordMatch' => ['password', 'match', 'pattern' => User::$passwordRegexp, 'message' => User::$passwordRegexpExplain],
//            'passwordRepeat' => ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => \Yii::t('site', 'Phone'),
            'first_name' => \Yii::t('site', 'What is your name?'),
            'email' => 'E-mail',
            'username' => 'E-mail',
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'user___head__registration_form';
    }


    /**
     * Username and email is the same in our system     *
     *
     * @return bool
     */
    public function beforeValidate()
    {
        $this->accept_terms = 1;
        $this->phone = preg_replace("/[^\+0-9]/", "", $this->phone);

        return parent::beforeValidate();
    }


    /**
     * Registers a new user account. If registration was successful it will set flash message.
     *
     * @return bool
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $ip = Yii::$app->getRequest()->getUserIP();

        $restricted = ['50.201.139.138', '46.101.98.81'];

        if (in_array($ip, $restricted)) {
            Yii::$app->end(0, Yii::$app->controller->redirect('/'));
        }

        if (strpos($this->first_name, 'Boorotype') !== false) {
            Yii::$app->end(0, Yii::$app->controller->redirect('/'));
        }

        /** @var User $user */
        $this->user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'create',
            'username' => $this->email,
            'v2' => 1,
            'password' => Password::generate(8),
            'registration_ip' => $ip,
            'email' => $this->email
        ]);

        /** @var Head $head */
        $head = Yii::createObject([
            'class' => Head::className(),
            'scenario' => 'create',
            'phone' => $this->phone,
            'first_name' => $this->first_name,
            'accept_terms' => $this->accept_terms
        ]);

        if (!$this->user->create($head)) {

            $this->addError('first_name', 'Не удалось зарегистрировать аккаунт: ' . strip_tags(Html::errorSummary([$this->user, $head], ['header' => false])));

            return false;
        }

        /** @var UserHeadManager $head_manager */
        $head_manager = \Yii::createObject([
            'class' => UserHeadManager::className(),
            'phone' => $this->phone
        ]);

        $head_manager->id = $this->user->id;
        $head_manager->save(false);

        AffAccount::register($head_manager);

        \Yii::$app->getModule('billing');
        \Yii::$app->getModule('script');
        BillingAccount::register($head_manager);

        /** @var Token $token */
        $token = Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
        $token->link('user', $this->user);
        (new HeadMailer())->sendWelcomeMessage($this->user, $token);

        Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались!');

        try {
            AmoLeadCreator::create($this->user->id, $this->first_name, $this->phone, $this->email);
        } catch (\Exception $e) {

        }

        return true;
    }


    /**
     * Loads attributes to the user model. You should override this method if you are going to add new fields to the
     * registration form. You can read more in special guide.
     *
     * By default this method set all attributes of this model to the attributes of User model, so you should properly
     * configure safe attributes of your User model.
     *
     * @param User $user
     */
    protected function loadAttributes(User $user)
    {
        $user->setAttributes($this->attributes);
    }
}
