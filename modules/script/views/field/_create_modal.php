<?php

/* @var $this yii\web\View */
/* @var $model app\modules\script\models\ar\Field */
/* @var $form  yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\Modal;

$form_id = 'script___field__create_form';
$modal_id = 'script___field__create_modal';
$url = '/script/field/create';

Modal::begin([
    'header' => Html::tag('h4', Yii::t('script', 'Add field')),
    'id' => $modal_id,
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::button(Yii::t('site', 'Create'), ['class' => 'btn btn-success core___functions__trigger_modal_form_submit']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

echo $this->render('_form', ['form_id' => $form_id, 'modal_id' => $modal_id, 'url' => $url, 'model' => $model, 'saved' => $saved]);

Modal::end();