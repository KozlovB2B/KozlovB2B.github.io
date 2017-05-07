<?php

/* @var $this yii\web\View */
/* @var $model app\modules\script\models\form\EditorOptionsForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $saved boolean */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\script\models\form\EditorOptionsForm;

Modal::begin([
    'header' => Html::tag('strong', 'Настройки редактора'),
    'id' => 'script___script__editor_options_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::button('Сохранить', ['class' => 'btn btn-success core___functions__trigger_modal_form_submit']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

Pjax::begin(['id' => 'script___script__editor_options_form_container', 'enablePushState' => false, 'enableReplaceState' => false]);

$form = ActiveForm::begin(['id' => 'script___script__editor_options_form', 'action' => '/script/script/editor-options?id=' . $model->getScript()->id, 'options' => ['data-pjax' => true, 'data-push' => false]]);

if ($saved) {
    $this->registerJs('$("#script___script__editor_options_modal").modal("hide")');
    $this->registerJs('message("success", "Настройки редактора сохранены!");');
    $this->registerJs('Yiij.app.getModule("editor").loadOptions(' . json_encode($model->getAttributes()) . ')');
    $this->registerJs('Yiij.app.getModule("editor").applyOptions()');
}

?>
<?= $form->field($model, 'arrow_style')->dropDownList(EditorOptionsForm::arrowStyles()) ?>
<?= $form->field($model, 'node_content_max_height')->textInput()->hint('Если указать 0 &mdash; высота текста не будет ограничена.', ['class' => 'small help-hint']) ?>
<?= $form->field($model, 'as_default')->checkbox()->hint('Будет использоваться для всех скриптов, где не менялись настройки редактора.', ['class' => 'small help-hint']) ?>
<?php
ActiveForm::end();

Pjax::end();

Modal::end();