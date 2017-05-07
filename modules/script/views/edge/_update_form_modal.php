<?php
/* @var app\modules\script\models\Script $script */
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use rmrevin\yii\fontawesome\FA;

$edge = new \app\modules\script\models\Edge();


$form = ActiveForm::begin([
    'action' => '#',
    'id' => "script___edge___update_form",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Edit answer')),
    'id' => 'script___edge___update_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>
<?= Html::hiddenInput("script___edge___update_form_id", null, ["id" => "script___edge___update_form_id"]); ?>
<?= $form->field($edge, 'content')->textarea(["id" => "script___edge___update_form_content", 'rows' => 5]) ?>
<?= $form->field($edge, 'target')->dropDownList($script->getNodesForStartNodeSelect(), ["id" => "script___edge___update_form_target", "prompt" => "-- " . $edge->getAttributeLabel('target')])->label(false) ?>
<?php
Modal::end();
ActiveForm::end();