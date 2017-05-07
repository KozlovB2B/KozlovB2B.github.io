<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/** @var string $id */
/** @var app\modules\user\models\HeadRegistrationForm $register */

$form = ActiveForm::begin([
    'id' => $id,
    'action' => '/user/head/register',
    'options' => ["class" => "form-inline registration-form"],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false
]); ?>
    <input style="display:none"><input type="password" style="display:none">
<?= $form->field($register, 'first_name')->textInput(['placeholder' => 'Ваше имя'])->label(false) ?>
<?= $form->field($register, 'email')->textInput(['placeholder' => 'Ваш e-mail'])->label(false) ?>
<?= $form->field($register, 'phone')->textInput(['placeholder' => 'Телефон для связи'])->label(false) ?>

    <div class="form-group">

        <?= Html::submitButton('Получить доступ', ['class' => 'btn btn-success btn-block']) ?>

        <div class="text-center"><small><i>14-дневный пробный период</i></small></div>
    </div>
<?php ActiveForm::end(); ?>