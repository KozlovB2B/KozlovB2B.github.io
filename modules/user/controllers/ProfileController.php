<?php

namespace app\modules\user\controllers;

use app\modules\core\components\AjaxValidationTrait;
use app\modules\user\models\ChangeAvatarForm;
use app\modules\user\models\ChangePasswordForm;
use app\modules\user\models\profile\ProfileRelation;
use app\modules\user\models\ProfileCreateForm;
use Yii;
use app\modules\core\components\BaseController;
use app\modules\user\models\profile\Profile;
use yii\bootstrap\ActiveForm;
use app\modules\user\models\User;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;

class ProfileController extends BaseController
{
    use AjaxValidationTrait;

    /**
     * Подгрузка полей профиля в форму создания пользователя
     *
     * @param $profile
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionCreateForm($profile)
    {
        $this->denyNotAjax();
        $this->checkAccess('user___user__create');
        $model = Yii::createObject(ProfileRelation::profileClassFullName($profile));
        return $this->renderPartial('create-form/_' . strtolower($profile), ['model' => $model, 'form' => new ActiveForm()]);
    }

    /**
     * Подгрузка полей профиля в форму создания пользователя
     *
     * @param $id
     * @param $profile
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdateForm($id, $profile)
    {
        $this->denyNotAjax();

        $user = $this->findUserModel($id);

        $this->checkAccess('user___user__update', ['user' => $user]);

        return $this->renderPartial('update-form/_' . strtolower($profile), ['model' => $user->getProfile($profile), 'form' => new ActiveForm()]);
    }

    /**
     * Страница настроек профиля
     *
     * @return string
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdate()
    {
        $this->checkAccess('user___profile__update_own');

        /** @var Profile $profile */
        $profile = Yii::$app->getUser()->identity->profile;
        $profile->setScenario('update-by-user');

        $this->ajaxValidation($profile);

        if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
            return $this->result('Данные профиля успешно обновлены!');
        }

        return $this->render('update', [
            'profile' => $profile,
            'change_password' => Yii::createObject(ChangePasswordForm::className()),
            'change_avatar' => Yii::createObject(ChangeAvatarForm::className()),
        ]);
    }

    /**
     * Переключение между профилями
     *
     * @param $to
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionSwitch($to)
    {
        $this->checkAccess('user___profile__update_own');
        /** @var User $user */
        $user = Yii::$app->getUser()->identity;
        $user->getProfile($to);
        /** @var ProfileRelation $rel */
        $rel = ProfileRelation::find()->where(['user_id' => $user->id, 'profile_class' => $to])->one();
        $rel->is_current = 1;
        $rel->save(false);
        $this->redirect('/');
    }

    /**
     * Обновление профиля администратором
     *
     * @param $id
     * @return string
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdateByAdmin($id)
    {
        $this->denyNotAjax();

        if (!Yii::$app->getRequest()->post('profile')) {
            return $this->result('Не указан тип профиля', 'error');
        }

        $user = $this->findUserModel($id);

        $this->checkAccess('user___user__update', ['user' => $user]);

        $profile = $user->getProfile(Yii::$app->getRequest()->post('profile'));

        $this->ajaxValidation($profile);

        if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
            return $this->result('Данные профиля успешно обновлены!');
        }

        return $this->result(Html::errorSummary($profile), 'error');
    }

    /**
     * Удаление профиля
     *
     * @param $id
     * @param $profile
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDelete($id, $profile)
    {
        $this->denyNotAjax();

        $user = $this->findUserModel($id);

        $this->checkAccess('user___user__update', ['user' => $user]);

        /** @var ProfileRelation $rel */
        $rel = ProfileRelation::find()->where(['user_id' => $user->id, 'profile_class' => $profile])->one();

        if (!$rel->delete()) {
            return $this->result(Html::errorSummary($rel), 'error');
        }

        $rel->getProfile()->delete();

        $role = Yii::$app->getAuthManager()->getRole($rel->getProfile()->getRole());
        Yii::$app->getAuthManager()->revoke($role, $rel->user_id);

        return $this->result('Профиль был удален!');
    }

    /**
     * Создание пользователя или приглашение его в систему
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException.
     */
    public function actionCreate($id)
    {
        $user = $this->findUserModel($id);

        $this->checkAccess('user___user__update', ['user' => $user]);

        /** @var ProfileCreateForm $model */
        $model = Yii::createObject([
            'class' => ProfileCreateForm::className(),
            'user' => $user
        ]);

        if (!$model->profilesAvailable()) {
            return $this->result('У пользователя уже есть все существующие типы профиля!', 'error');
        }

        if (Yii::$app->getRequest()->post()) {
            $model->load(Yii::$app->request->post());
            $model->getProfile()->setScenario('create');
            $model->getProfile()->load(Yii::$app->request->post());

            $this->ajaxValidation($model);
            $this->ajaxValidation($model->getProfile());

            if ($model->create()) {
                return $this->result('Профиль был добавлен!');
            } else {
                return $this->result(Html::errorSummary([$model, $model->getProfile()], ['header' => false]), 'error');
            }
        }

        return $this->renderPartial('_create_modal', ['model' => $model]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return User                  the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findUserModel($id)
    {
        $user = User::findOne($id);
        if ($user === null) {
            throw new NotFoundHttpException('User not found!');
        }
        return $user;
    }
}