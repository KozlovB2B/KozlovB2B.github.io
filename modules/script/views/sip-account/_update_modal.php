<?php
/* @var app\modules\script\models\SipAccount $model */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'action' => '/script/sip-account/update?id=' . $model->id,
    'id' => "script___sip_account__update_form",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);

Modal::begin([
    'header' => 'SIP: ' . $model->user->username ,
    'id' => 'script___sip_account__update_modal',
    'closeButton' => false,
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Save'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>
<?= $form->field($model, 'display_name')->textInput(['placeholder' => 'Ivan Ivanov']) ?>
<?= $form->field($model, 'private_identity')->textInput(['placeholder' => 'user007']) ?>
<?= $form->field($model, 'realm')->textInput(['placeholder' => 'realm.com']) ?>
<?= $form->field($model, 'password')->textInput() ?>
<?php
Modal::end();
ActiveForm::end();