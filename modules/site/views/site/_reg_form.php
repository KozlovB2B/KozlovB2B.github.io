<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/** @var string $id */
/** @var app\modules\user\models\HeadRegistrationForm $register */

$form = ActiveForm::begin([
    'id' => $id,
    'action' => '/user/register',
    'options' => ["class" => "form-inline registration-form"],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false
]); ?>
    <input style="display:none"><input type="password" style="display:none">
<?= $form->field($register, 'name')->textInput(['placeholder' => 'First name'])->label(false) ?>
<?= $form->field($register, 'email')->textInput(['placeholder' => 'Your e-mail'])->label(false) ?>
<?= $form->field($register, 'phone')->textInput(['placeholder' => 'Your phone'])->label(false) ?>
    <div class="form-group">

        <?= Html::submitButton('Create my account', ['class' => 'btn btn-success btn-block']) ?>

        <div class="text-center"><small><i>no credit card required</i></small></div>
    </div>


<?php ActiveForm::end(); ?>