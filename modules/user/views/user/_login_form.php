<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\modules\user\models\LoginForm $login */

$form = ActiveForm::begin([
    'id' => $login->formName(),
    'action' => '/login',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false
]); ?>
    <fieldset>
        <legend>
            Вход

            <?= Html::a('Регистрация', Url::to(['/user/head/register']), ['class' => 'pull-right legend-link']) ?>
        </legend>

        <?= $form->field($login, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'tabindex' => '1']]) ?>

        <?= $form->field($login, 'password', ['inputOptions' => ['tabindex' => '2']])
            ->passwordInput()
            ->label($login->getAttributeLabel('password') . ' (' . Html::a('Забыли пароль?', Url::to(['/user/password-recovery/request']), ['tabindex' => '5']) . ')')
        ?>
        <?= Html::submitButton('Войти', ['class' => 'btn btn-success btn-block']) ?>
    </fieldset>
<?php ActiveForm::end(); ?>