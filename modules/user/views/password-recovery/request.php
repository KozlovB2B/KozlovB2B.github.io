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
$this->title = Yii::t('site', 'Recover password');

Pjax::begin(['id' => 'user___user__forgot_password_container', 'enablePushState' => false]);

if ($saved) {
    echo Html::tag('div', "Письмо со ссылкой для восстановления пароля выслано на ваш e-mail!", ["class" => "alert alert-success"]);
} else {
    ?>

    <?php $form = ActiveForm::begin(['id' => 'user___user__forgot_password_form', 'action' => Url::to(['/user/password-recovery/request']), 'options' => ['data-pjax' => true]]); ?>
    <fieldset>
        <legend><?= Html::encode($this->title) ?></legend>
        <?= $form->field($model, 'username_or_email')->textInput(['autofocus' => true]) ?>
    </fieldset>


    <?= Html::submitButton(Yii::t('site', 'Recover password'), ['class' => 'btn btn-success btn-block'] ); ?>

    <?php ActiveForm::end(); ?>
<?php } ?>
<?php
Pjax::end();