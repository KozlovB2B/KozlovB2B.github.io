<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/** @var app\modules\user\models\PasswordRecoveryForm $model */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $saved boolean */

Modal::begin([
    'header' => Html::tag('strong', Yii::t('site', 'Recover password')),
    'id' => 'user___user__forgot_password_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::button(Yii::t('site', 'Recover password'), ['class' => 'btn btn-success core___functions__trigger_modal_form_submit']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

Pjax::begin(['id' => 'user___user__forgot_password_container', 'enablePushState' => false]);

if ($saved) {
    $this->registerJs('$("#user___user__forgot_password_modal").modal("hide")');
    $this->registerJs('message("success", "Письмо со ссылкой для восстановления пароля выслано на ваш e-mail!");');
}

?>
<?php $form = ActiveForm::begin(['id' => 'user___user__forgot_password_form', 'action' => Url::to(['/user/user/forgot-password']), 'options' => ['data-pjax' => true]]); ?>

<?= $form->field($model, 'username_or_email')->textInput(['autofocus' => true]) ?>

<?php ActiveForm::end() ?>

<?php

Pjax::end();

Modal::end();