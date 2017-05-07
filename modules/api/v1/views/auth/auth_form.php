<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\modules\site\models\LoginForm $model */

$form = ActiveForm::begin([
    'action' => Url::to(['/api/v1/auth/auth']),
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
]); ?>

<?= $form->field($model, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]) ?>

<?= $form->field($model, 'password', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])->passwordInput()->label(Yii::t('user', 'Password') . ' (' . Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5']) . ')') ?>
<?= Html::submitButton(Yii::t('site', 'Log in'), ['class' => 'btn btn-signin btn-success']); ?>
&nbsp;
&nbsp;
&nbsp;
<?= Html::a(Yii::t('site', 'Registration'), Url::to(['/#reg']), ['target' => '_blank', 'class' => '']); ?>
<?php
ActiveForm::end();