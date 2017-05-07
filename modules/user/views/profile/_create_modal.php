<?php
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\ProfileCreateForm */
/* @var $form ActiveForm */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\models\profile\ProfileRelation;
use app\modules\user\models\UserCreateForm;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'action' => '/user/profile/create?id=' . $model->user->id,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
Modal::begin([
    'header' => Html::tag('h4', 'Добавить профиль'),
    'id' => 'user___profile__create_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton('Добавить профиль', ['class' => 'btn btn-success']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]); ?>
<?= $form->field($model, 'profile_class')->dropDownList($model->profilesAvailable()) ?>
<?= Html::tag('div', $this->render('@app/modules/user/views/profile/create-form/_' . $model->getProfile()->getCode(), ['model' => $model->getProfile(), 'form' => $form]), ['id' => $model->formName() . '-profile_fields']) ?>
<?php
Modal::end();
ActiveForm::end();