<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\core\components\assets\TimezoneJsAsset;

TimezoneJsAsset::register($this);

$this->title = 'Регистрация по приглашению';
?>
<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]);
?>
<fieldset>
    <legend><?= Html::encode($this->title) ?></legend>
    <small class="text-info">
        Вы были приглашены в <?= Yii::$app->name ?> в качестве <?= $model->user->profile->getName('accusative') ?>.<br/>
        Ваш email - <?= $model->user->email ?>.<br/>
        Чтобы завершить регистрацию - заполните форму ниже и нажмите кнопку.
    </small>
    <br/>
    <br/>

    <?= $form->field($model, 'username')->textInput() ?>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'password_repeat')->passwordInput() ?>
        </div>
    </div>

    <?= $form->field($model, 'timezone_id')->hiddenInput(['class' => 'form-control', 'id' => 'user___user__accept_invite_form_timezone_id'])->label(false) ?>

    <?php $this->registerJs('$("#user___user__accept_invite_form_timezone_id").val(jstz.determine().name())'); ?>

    <?= $this->render('@app/modules/user/views/profile/accept-invite-form/_' . $model->user->profile->getCode(), ['model' => $model->user->profile, 'form' => $form]); ?>
</fieldset>

<?= Html::submitButton('Завершить регистрацию', ['class' => 'btn btn-success btn-block']) ?><br>
<?php ActiveForm::end(); ?>
