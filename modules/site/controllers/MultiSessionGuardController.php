<?php
namespace app\modules\site\controllers;

use app\modules\site\models\MultiSessionGuard;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use app\modules\core\components\Url;

class MultiSessionGuardController extends Controller
{
    /**
     * Asking user to destroy ol another sessions of his account before login
     *
     * @param string $t Guard token to destroy old user sessions
     * @return int
     */
    public function actionAsk($t)
    {
        $this->layout = "@app/modules/site/views/layouts/landing";
        return $this->render('ask', ['model' => $this->findModel($t)]);
    }

    /**
     * Asking user to destroy ol another sessions of his account before login
     *
     * @param string $t Guard token to destroy old user sessions
     * @param string $redirect Redirect back after asking or terminations
     * @return int
     */
    public function actionAskTerminateOtherSessions($t, $redirect)
    {
        $this->layout = "@app/modules/site/views/layouts/landing";
        return $this->render('ask_terminate_other_sessions', ['model' => $this->findModel($t), 'redirect' => $redirect]);
    }

    /**
     * Asking user to destroy ol another sessions of his account before login
     *
     * @param string $t Guard token to destroy old user sessions
     * @param string $redirect Redirect back after asking or terminations
     * @param string $decline_url Redirect if user choose to keep sessions
     * @return int
     */
    public function actionAskTerminateOtherSessionsApi($t, $redirect, $decline_url = '/')
    {
        $this->layout = "@app/modules/api/views/layout/gui";

        $model = MultiSessionGuard::find()->where('token=:t', [':t' => $t])->one();

        if (!$model) {
            return $this->redirect(Url::to(['/api/v1/script/index']));
        }

        return $this->render('ask_terminate_other_sessions_api', ['model' => $model, 'redirect' => $redirect, 'decline_url' => $decline_url]);
    }

    /**
     * Use token and auth user
     *
     * @param string $t Guard token to destroy old user sessions
     * @return int
     */
    public function actionUse($t)
    {
        $this->findModel($t)->useToken();
        return $this->goHome();
    }

    /**
     * Use token and auth user
     *
     * @param string $t Guard token to destroy old user sessions
     * @param string $redirect Redirect back after asking or terminations
     * @return int
     */
    public function actionTerminateOtherSessions($t, $redirect)
    {
        $this->findModel($t)->terminateOtherSessions();
        return $this->redirect($redirect);
    }

    /**
     * Use token and auth user
     *
     * @param string $t Guard token to destroy old user sessions
     * @param string $redirect Redirect back after asking or terminations
     * @return int
     */
    public function actionTerminateOtherSessionsAjax($t)
    {
        $this->findModel($t)->terminateOtherSessions();

        return 'ok';
    }

    /**
     * Finds the User model based on its token value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  string $t
     * @return MultiSessionGuard                  the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($t)
    {
        $user = MultiSessionGuard::find()->where('token=:t', [':t' => $t])->one();
        if ($user === null) {
            throw new NotFoundHttpException('Requested token not found!');
        }
        return $user;
    }

}