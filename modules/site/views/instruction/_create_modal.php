<?php
/** @var Instruction $model */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\site\models\Instruction;

$form = ActiveForm::begin([
    'id' => 'site___instruction__create_form',
    'action' => '/site/instruction/create',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
Modal::begin([
    'header' => Html::tag("strong", Yii::t('site', "Add instruction")),
    'id' => 'site___instruction__create_modal',
    'size' => Modal::SIZE_DEFAULT,
    'footer' => Html::submitButton(Yii::t('site', 'Save'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]); ?>
<?= $form->field($model, 'status_id')->dropDownList(Instruction::getStatuses()) ?>
<?= $form->field($model, 'video')->textInput() ?>
<?= $form->field($model, 'description')->textInput() ?>
<?= $form->field($model, 'content')->textarea() ?>
<?php
Modal::end();
ActiveForm::end();