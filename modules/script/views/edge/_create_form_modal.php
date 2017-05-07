<?php
/* @var app\modules\script\models\Script $script */

use app\modules\user\models\OperatorRegistrationForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$edge = new \app\modules\script\models\Edge();

$form = ActiveForm::begin([
    'action' => '/script/script/edge?id='.$script->id,
    'id' => "script___edge___create_form",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Add an answer')),
    'id' => 'script___edge___create_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>
<?= $form->field($edge, 'source')->hiddenInput(["id" => "script___edge___create_form_source"])->label(false) ?>
<?= $form->field($edge, 'content')->textarea(["id" => "script___edge___create_form_content", 'rows' => 5]) ?>
<?= $form->field($edge, 'target')->dropDownList($script->getNodesForStartNodeSelect(), ["id" => "script___edge___create_form_target", "prompt" => "-- " . $edge->getAttributeLabel('target')])->label(false) ?>

<?php
Modal::end();
ActiveForm::end();