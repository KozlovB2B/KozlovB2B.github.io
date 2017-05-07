<?php
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \yii\helpers\Url;

/** @var app\modules\user\models\LoginForm $login */

$form = ActiveForm::begin([
    'id' => 'signin-form',
    'action' =>  Url::to(["/user/user/login"]),
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
]);
Modal::begin([
    'header' => Html::tag("strong", Yii::t('site', 'Log in')),
    'id' => 'signin',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Log in'), ['class' => 'btn btn-signin'])]); ?>

<?= $form->field($login, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']])->label(Yii::t('site', 'Login (email)')) ?>

<?= $form->field($login, 'password', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])->passwordInput()->label(Yii::t('user', 'Password') . ' (' . Html::a(Yii::t('user', 'Forgot password?'), ['/user/password-recovery/request'], []) . ')') ?>
<?php
Modal::end();
ActiveForm::end();