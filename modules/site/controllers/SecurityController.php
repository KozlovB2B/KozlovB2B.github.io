<?php

namespace app\modules\site\controllers;

use Yii;
use app\modules\site\models\LoginForm;
use app\modules\site\models\MultiSessionGuard;

class SecurityController extends BaseController
{
    /**
     * Displays the login page.
     *
     * @return string|\yii\web\Response
     */
//    public function actionLogin()
//    {
//        if (!Yii::$app->user->isGuest) {
//            $this->goHome();
//        }
//
//        /** @var LoginForm $model */
//        $model = Yii::createObject(LoginForm::className());
//
//        $this->performAjaxValidation($model);
//
//        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
//            if(!Yii::$app->getAuthManager()->checkAccess($model->getUser()->id, 'admin') && MultiSessionGuard::check($model->user->id)){
//                return $this->redirect('/guard?t=' . MultiSessionGuard::create($model->getUser()->id));
//            }
//
//            if ($model->login()) {
//                return $this->goBack();
//            }
//        }
//
//        return $this->render('login', [
//            'model' => $model,
//            'module' => $this->module,
//        ]);
//    }

    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = Yii::createObject(LoginForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout(true);

        return $this->goHome();
    }
}
