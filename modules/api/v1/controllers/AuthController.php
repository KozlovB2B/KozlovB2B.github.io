<?php
namespace app\modules\api\v1\controllers;

use app\modules\api\v1\components\BaseController;
use app\modules\core\components\Url;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\modules\user\models\LoginForm;

/**
 * ScriptController
 */
class AuthController extends Controller
{
    /** @inheritdoc */
    public function init()
    {
        parent::init();
        $this->layout = "@app/modules/api/views/layout/gui";

        Yii::$app->getModule('site');
        Yii::$app->getModule('script');
    }

    /**
     * @return Response
     */
    protected function seeScripts()
    {
        return $this->redirect(Url::to(['/api/v1/script/index']));
    }

    /**
     * Авторизация пользователя по логину и паролю
     *
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAuth()
    {
        return $this->redirect('/login');
//        $this->layout = false;

        if (!Yii::$app->user->isGuest) {
            return $this->seeScripts();
        }

        /** @var LoginForm $model */
        $model = Yii::createObject(LoginForm::className());

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->seeScripts();
        }

        return $this->render('auth_form', [
            'model' => $model
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(Url::to(['/api/v1/auth/auth']));
    }
}