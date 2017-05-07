<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\HeadRegistrationForm;
use app\modules\core\components\AjaxValidationTrait;
use app\modules\core\components\BaseCoreController;

/**
 * Class HeadController
 *
 * Разные действия с аккаунтом оператора
 *
 * @package app\modules\user\controllers.
 */
class HeadController extends BaseCoreController
{
    use AjaxValidationTrait;

    /**
     * Показывает страницу регистрации оператора.
     * После успешной регистрации перенаправляет оператора на страницу с сообщением о завершении регистрации.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
    {
        /** @var HeadRegistrationForm $register */
        $register = Yii::createObject(HeadRegistrationForm::className());

        $this->performAjaxValidation($register);

        if ($register->load(Yii::$app->request->post()) && $register->register()) {
            Yii::$app->getUser()->login($register->user, 1209600);

            return $this->redirect('/welcome');
        }

        $this->layout = "@app/modules/site/views/layouts/standalone_public";

        return $this->render('register', [
            'register' => $register
        ]);
    }

    /**
     * @return string
     */
    public function actionDashboard()
    {
        $this->checkAccess('user___head__dashboard');

        return $this->render('dashboard');
    }

    /**
     * @return string
     */
    public function actionTeamInviteButtons()
    {
        $this->checkAccess('user___head__dashboard');

        return $this->renderAjax('_team_invite_buttons');
    }
}
