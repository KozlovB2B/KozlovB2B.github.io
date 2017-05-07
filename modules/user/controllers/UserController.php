<?php

namespace app\modules\user\controllers;

use app\modules\user\models\ChangeAvatarForm;
use app\modules\user\models\ChangePasswordForm;
use app\modules\user\models\PasswordRecoveryForm;
use app\modules\user\models\profile\ProfileRelation;
use app\modules\user\models\UserCreateForm;
use app\modules\user\models\AcceptInviteForm;
use Yii;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use app\modules\user\models\Token;
use yii\web\Response;

use app\modules\core\components\AjaxValidationTrait;
use app\modules\core\components\BaseCoreController;
use app\modules\user\models\User;
use app\modules\user\models\LoginForm;
use app\modules\user\models\UserSearch;
use yii\web\UploadedFile;
use app\modules\user\models\UserHeadManagerSearch;
use app\modules\user\models\UserHeadManager;

class UserController extends BaseCoreController
{
    use AjaxValidationTrait;

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();

        return $this->goHome();
    }

    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            $this->goHome();
        }

        /** @var LoginForm $login */
        $login = Yii::createObject(LoginForm::className());

        $this->performAjaxValidation($login);

        if ($login->load(Yii::$app->getRequest()->post()) && $login->login()) {
            return $this->goBack();
        }

        $this->layout('standalone_public');


        return $this->render('login', ['login' => $login]);
    }

    /**
     * Авторизация по ключу
     *
     * @param $id
     * @param $key
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionLoginUsingKey($id, $key)
    {
        $user = $this->findModel($id);

        if ($key == $user->auth_key) {
            Yii::$app->getUser()->login($user, User::REMEMBER_FOR);
            return $this->redirect('/');
        }
    }

    /**
     * Авторизация по ключу
     *
     * @param $id
     * @param $key
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionLoginV2($id, $key)
    {
        $user = $this->findModel($id);

        if ($key == $user->auth_key) {
            Yii::$app->getUser()->login($user, User::REMEMBER_FOR);

            return $this->redirect('/conversion');
        }

        return null;
    }


    /**
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise redirects to home page.
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionAdmin()
    {
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
     * Создание пользователя или приглашение его в систему
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException.
     */
    public function actionCreate()
    {
        $this->checkAccess('user___user__create');

        /** @var UserCreateForm $model */
        $model = \Yii::createObject(UserCreateForm::className());

        $model->setScenario(Yii::$app->getRequest()->post('scenario', 'create'));
        $this->ajaxValidation($model);
        $model->getProfileModel()->setScenario($model->getScenario());
        $this->ajaxValidation($model->getProfileModel());

        if ($model->load(Yii::$app->request->post())) {

            if ($model->performScenario()) {
                return $this->result('Пользователь был создан.' . ($model->scenario == 'invite' ? 'На указанный email было выслано пригласительное письмо для завершения регистрации.' : null));
            } else {
                return $this->result(Html::errorSummary([$model, $model->getProfileModel()], ['header' => false]), 'error');
            }
        }

        return $this->renderPartial('_create_modal', ['model' => $model]);
    }


    /**
     * Отображает страницу, где пользователь может завершить регистрацию по приглашению
     *
     * @param $id
     * @param $code
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAcceptInvite($id, $code)
    {
        /** @var Token $token */
        $token = Token::find()->where(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_INVITE])->one();

        if ($token === null || $token->isExpired || $token->user === null) {
            return $this->message('danger', 'Пригласительная ссылка недействительна или устарела. Пожалуйста запросите новую.');
        }

        /** @var AcceptInviteForm $model */
        $model = Yii::createObject(AcceptInviteForm::className());
        $model->user = $token->user;
        $model->username = $model->user->username;
        $model->user->profile->setScenario('accept-invite');


        if (Yii::$app->getRequest()->post()) {
            $this->performAjaxValidationMultiple($model, $model->user->profile);
            $model->load(Yii::$app->getRequest()->post());
            $model->user->profile->load(Yii::$app->getRequest()->post());

            if ($model->accept()) {
                if ($model->user->profile->welcomePage()) {
                    return $this->redirect($model->user->profile->welcomePage());
                } else {
                    return $this->message('success', 'Вы успешно завершили регистрацию!');
                }
            }
        }

        $this->layout('standalone_public');

        return $this->render('accept_invite', [
            'model' => $model,
        ]);
    }

    /**
     * Создание пользователя
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException.
     */
    public function actionChangePassword()
    {
        $this->checkAccess('user___profile__update_own');

        /** @var ChangePasswordForm $model */
        $model = \Yii::createObject(ChangePasswordForm::className());

        $this->ajaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->perform()) {
            return $this->result('Пароль был успешно изменен!');
        }

        return $this->render('change_password', ['model' => $model]);
    }

    /**
     * Загрузка аватарки
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException.
     */
    public function actionAvatar()
    {
        $this->checkAccess('user___profile__update_own');

        /** @var ChangeAvatarForm $model */
        $model = \Yii::createObject(ChangeAvatarForm::className());

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
                return $this->result('Ава загружена!', 'success', ['url' => $model->avatar->getUrl()]);
            } else {
                return $this->result(Html::errorSummary($model), 'error');
            }
        }

        return $this->render('change_avatar', ['model' => $model]);
    }

    /**
     * Редактируем данные профиля
     *
     * @param $id
     * @return string
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        $this->checkAccess('user___user__update', ['user' => $user]);

        $profile = $user->getProfile();

        if (Yii::$app->getRequest()->post()) {

            $profile_class = Yii::$app->getRequest()->post('profile');

            if (!$profile_class) {
                throw new BadRequestHttpException("Укажите данные какого профиля будете обновлять.");
            }

            /** @var ProfileRelation $profile_relation */
            $profile_relation = ProfileRelation::find()->where(['user_id' => $user->id, 'profile_class' => $profile_class])->one();

            if (!$profile_relation) {
                throw new BadRequestHttpException("У пользователя $user->username нет профиля $profile_class");
            }

            $profile = $profile_relation->getProfile();
            $this->performAjaxValidationMultiple($profile);
            $profile->load(Yii::$app->getRequest()->post());

            if ($profile->save()) {
                Yii::$app->session->setFlash('success', 'Данные профиля успешно обновлены.');
            }
        }

        return $this->render('update', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Просмотр списка пользователей
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionIndex()
    {
        $this->checkAccess('user___user__index');

        /** @var UserSearch $search */
        $search = \Yii::createObject(UserSearch::className());
        $data_provider = $search->search(\Yii::$app->request->get());


        return $this->render('index', [
            'search' => $search,
            'data_provider' => $data_provider,
        ]);
    }


    /**
     * Blocks the user.
     * @param  integer $id
     * @return Response
     */
    public function actionBlock($id)
    {
        $this->denyNotAjax();

        if ($id == Yii::$app->user->getId()) {
            return $this->result('Вы не можете заблокировать свой аккаунт!', 'error');
        }

        $user = $this->findModel($id);

        $this->checkAccess('user___user__update', ['user' => $user], 'Вам запрещено блокировать этого пользователя!');

        if ($user->getIsBlocked()) {
            $user->unblock();
            return $this->result('Пользователь был разблокирован!');
        } else {
            $user->block();
            return $this->result('Пользователь был заблокирован!');
        }
    }

    /**
     * Подтверждает аккаунт пользователя
     *
     * @param int $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code = null)
    {
        if (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->can('user___account__manage')) {
            $this->findModel($id)->confirm();
            return $this->redirect('/user/user/admin');
        } else {
            $this->findModel($id)->attemptConfirmation($code);

            return $this->redirect('/');
        }
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
        $user = User::findOne($id);

        if ($user === null) {
            throw new NotFoundHttpException('User not found!');
        }

        return $user;
    }
}