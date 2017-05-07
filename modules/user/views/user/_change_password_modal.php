<?php
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var \app\modules\user\models\ChangePasswordForm $model */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\components\ChangePasswordAssetBundle;

ChangePasswordAssetBundle::register($this);

$this->registerJs('new ChangePasswordForm();');

?>
<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'method' => 'post',
    'action' => '/user/user/change-password',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);

Modal::begin([
    'header' => Html::tag('h4', 'Сменить пароль'),
    'id' => 'user___user__change_password_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton('Сменить пароль', ['class' => 'btn btn-primary']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'new_password')->passwordInput() ?>
<?= $form->field($model, 'new_password_repeat')->passwordInput() ?>
<?php Modal::end(); ?>
<?php ActiveForm::end(); ?>