<?php

/* @var $this yii\web\View */
/* @var $model app\modules\script\models\ar\Field */
/* @var $form  yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\Modal;

$form_id = 'script___field__update_form';
$modal_id = 'script___field__update_modal';
$url = ['/script/field/update', 'id' => $model->id];

Modal::begin([
    'header' => Html::tag('h4', Yii::t('script', 'Update field')),
    'id' => $modal_id,
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::button(Yii::t('site', 'Save'), ['class' => 'btn btn-success core___functions__trigger_modal_form_submit']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

echo $this->render('_form', ['form_id' => $form_id, 'modal_id' => $modal_id, 'url' => $url, 'model' => $model, 'saved' => $saved]);

Modal::end();