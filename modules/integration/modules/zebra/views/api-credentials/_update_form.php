<?php
use app\modules\core\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $saved boolean */
/* @var $model app\modules\integration\modules\zebra\models\ApiCredentials */

Pjax::begin(['id' => 'integration___zebra__api_credentials_create_form_container', 'enablePushState' => false]);

if ($saved) {
    $this->registerJs('message("success", "' . Yii::t('zebra', 'Saved!') . '");');
}

$form = ActiveForm::begin([
    'id' => 'integration___zebra__api_credentials_create_form',
    'action' => '/integration/zebra/api-credentials/update',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'options' => ['data-pjax' => true]
]); ?>

<?= $form->field($model, 'login')->textInput()->hint(Html::hint(Yii::t('zebra', 'Логин администратора в облачной ATC. Например: admin'))); ?>
<?= $form->field($model, 'password')->textInput()->hint(Html::hint(Yii::t('zebra', 'Пароль администратора в облачной ATC'))); ?>
<?= $form->field($model, 'realm')->textInput()->hint(Html::hint(Yii::t('zebra', 'Домен облачной АТС. Например: 12345.ztpbx.ru'))); ?>

<?= Html::submitButton(Yii::t('integration', 'Save'), ['class' => 'btn btn-success pull-right']) ?>

<?php

ActiveForm::end();

Pjax::end();