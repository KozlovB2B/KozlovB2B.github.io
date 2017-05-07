<?php
namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use app\modules\user\helpers\Password;

/**
 * Class ChangePasswordForm
 *
 * Смена пароля пользователем
 *
 * @package app\modules\user\models
 */
class ChangePasswordForm extends Model
{
    /** @var string Текущий пароль пользователя */
    public $password;

    /** @var string Новый пароль */
    public $new_password;

    /** @var string Повторить пароль */
    public $new_password_repeat;

    /** @var User */
    public $user;

    /** @inheritdoc */
    public function init()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            throw new ForbiddenHttpException('Гости не могут менять пароль - зарегайтесь сначала!');
        }

        $this->user = Yii::$app->getUser()->identity;

        parent::init();
    }


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'new_password_repeat' => 'Повторите новый пароль'
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'requiredFields' => [['password', 'new_password', 'new_password_repeat'], 'required'],
            'passwordValidate' => [
                'password',
                function ($attribute) {
                    if (!Password::validate($this->password, $this->user->password_hash)) {
                        $this->addError($attribute, 'Текущий пароль указан неверно!');
                    }
                }
            ],
            'passwordLength' => ['new_password', 'string', 'min' => 6],
            'passwordMatch' => ['new_password', 'match', 'pattern' => User::$passwordRegexp, 'message' => User::$passwordRegexpExplain],
            'passwordRepeat' => ['new_password_repeat', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли должны совпадать!'],
        ];
    }

    /**
     * Выполняет сброс пароля
     *
     * @return bool
     */
    public function perform()
    {
        if ($this->validate()) {
            return $this->user->resetPassword($this->new_password);
        }

        return false;
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'user___user__change_password_form';
    }
}
