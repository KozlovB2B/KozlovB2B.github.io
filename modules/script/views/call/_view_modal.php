<?php
/** @var OperatorRegistrationForm $model */
/* @var app\modules\script\models\Script $script */

use app\modules\user\models\OperatorRegistrationForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model = new \app\modules\script\models\Edge();

$form = ActiveForm::begin([
    'action' => '/script/script/edge?id='.$script->id,
    'id' => "script___edge___create_form",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Add answer')),
    'id' => 'script___edge___create_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>
<?= $form->field($model, 'source')->hiddenInput(["id" => "script___edge___create_form_source"])->label(false) ?>
<?= $form->field($model, 'content')->textarea(["id" => "script___edge___create_form_content"]) ?>
<?php
Modal::end();
ActiveForm::end();