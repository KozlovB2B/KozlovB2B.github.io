<?php

namespace app\modules\site\controllers;

use app\modules\core\components\CoreController;
use app\modules\script\models\ApiToken;
use app\modules\user\models\User;
use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\user\models\UserHeadManagerSearch;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Class UsersController
 * @package app\modules\site\controllers
 */
class UsersController extends CoreController
{

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['create-builds-manually', 'enable-hits-report'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ]
        ];
    }

    /**
     * User profile
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProfile()
    {
        $this->checkAccess('user___account__profile');
        $model = UserHeadManager::findHeadManagerByUser();
        $token = ApiToken::findOne(Yii::$app->getUser()->getId());

        if (!$token) {
            ApiToken::generate();
            $token = ApiToken::findOne(Yii::$app->getUser()->getId());
        }

        return $this->render('profile', [
            'model' => $model,
            'token' => $token,
        ]);
    }

    /**
     * User profile
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCreateBuildsManually($value)
    {
        $this->checkAccess('user___account__profile');
        $model = UserHeadManager::findHeadManagerByUser();
        $model->create_builds_manually = $value;
        $model->update(false, ['create_builds_manually']);
        return true;
    }
    /**
     * User profile
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionEnableHitsReport($value)
    {
        $this->checkAccess('user___account__profile');
        $model = UserHeadManager::findHeadManagerByUser();
        $model->hits_report = $value;
        $model->update(false, ['hits_report']);
        return true;
    }

    /**
     * View
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $this->checkAccess('user___account__manage');
        $model = $this->findUserHeadManagerModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * View
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionChangePassword($id)
    {
        $this->checkAccess('user___account__manage');

        $model = $this->findUserHeadManagerModel($id);

        if (Yii::$app->request->post()) {
            $model->user->resetPassword($_POST['User']['password']);
            $this->result('Пароль изменен!');
        } else {
            return $this->renderAjax('_change_password_modal', [
                'model' => $model
            ]);
        }
    }

    /**
     * Авторизация по ключу
     *
     * @param $id
     * @param $key
     * @throws NotFoundHttpException
     */
    public function actionLoginUsingKey($id, $key)
    {
        $user = $this->findModel($id);
        if ($key == $user->auth_key) {
            Yii::$app->getUser()->login($user);
            return $this->redirect('/');
        }
    }

    /**
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise redirects to home page.
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionAdmin()
    {
        Url::remember('', 'actions-admin');
        $this->checkAccess('user___account__manage');

        /** @var UserHeadManagerSearch $search */
        $search = \Yii::createObject(UserHeadManagerSearch::className());
        $data_provider = $search->search(\Yii::$app->request->get());


        return $this->render('admin', [
            'search' => $search,
            'data_provider' => $data_provider,
        ]);
    }

    /**
     * Confirms the User.
     * @param integer $id
     * @return Response
     */
    public function actionConfirm($id)
    {
        $this->findModel($id)->confirm();
        Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been confirmed'));

        return $this->redirect(Url::previous('actions-admin'));
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($id == Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'You can not remove your own account'));
        } else {
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been deleted'));
        }

        return $this->redirect(['admin']);
    }

    /**
     * Blocks the user.
     * @param  integer $id
     * @return Response
     */
    public function actionBlock($id)
    {
        if ($id == Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'You can not block your own account'));
        } else {
            $user = $this->findModel($id);
            if ($user->getIsBlocked()) {
                $user->unblock();
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been unblocked'));
            } else {
                $user->block();
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been blocked'));
            }
        }

        return $this->redirect(Url::previous('actions-admin'));
    }


    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return UserHeadManager                  the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findUserHeadManagerModel($id)
    {
        $user = UserHeadManager::find()->where('id=:id', [':id' => $id])->one();
        if ($user === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $user;
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return User                  the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $user = User::find()->where('id=:id', [':id' => $id])->one();
        if ($user === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $user;
    }
}