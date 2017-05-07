<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var app\modules\user\models\HeadRegistrationForm $register */
/** @var yii\web\View $this */

$form = ActiveForm::begin([
    'id' => $register->formName(),
    'action' => '/user/head/register',
    'options' => ["class" => "registration-form"],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false
]); ?>
    <input style="display:none"><input type="password" style="display:none">
<?= $form->field($register, 'first_name')->textInput(['placeholder' => 'Ваше имя'])->label(false) ?>
<?= $form->field($register, 'email')->textInput(['placeholder' => 'Ваш e-mail'])->label(false) ?>
<?= $form->field($register, 'phone')->textInput(['placeholder' => 'Телефон для связи'])->label(false) ?>
<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success btn-block']) ?>
<?php ActiveForm::end(); ?>