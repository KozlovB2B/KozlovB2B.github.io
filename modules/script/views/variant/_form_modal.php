<?php
/** @var string $form_id */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\script\models\ar\Variant;

$variant = new Variant();

Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Edit answer')),
    'id' => $form_id . '_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success', 'id' =>  $form_id . '_submit']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]);

$form = ActiveForm::begin([
    'action' => '#',
    'id' => $form_id,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);

?>

<?= $form->field($variant, 'content')->textarea() ?>

<?= $form->field($variant, 'target_id')->dropDownList([], ["prompt" => "-- " . $variant->getAttributeLabel('target_id')]) ?>

<?php

ActiveForm::end();
Modal::end();
