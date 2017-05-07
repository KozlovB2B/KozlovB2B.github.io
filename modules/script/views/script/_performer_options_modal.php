<?php

/* @var $this yii\web\View */
/* @var $model app\modules\script\models\form\PerformerOptionsForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $saved boolean */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\script\models\form\PerformerOptionsForm;


Modal::begin([
    'header' => Html::tag('strong', 'Настройки прогонщика'),
    'id' => 'script___script__performer_options_modal',
    'size' => Modal::SIZE_DEFAULT,
    'footer' => Html::button('Сохранить', ['class' => 'btn btn-success core___functions__trigger_modal_form_submit']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

Pjax::begin(['id' => 'script___script__performer_options_form_container', 'enablePushState' => false, 'enableReplaceState' => false]);

$form = ActiveForm::begin(['id' => 'script___script__performer_options_form', 'action' => '/script/script/performer-options?id=' . $model->getScript()->id, 'options' => ['data-pjax' => true, 'data-push' => false]]);

if ($saved) {
    $this->registerJs('$("#script___script__performer_options_modal").modal("hide")');
    $this->registerJs('message("success", "Настройки прогонщика сохранены!");');
}

?>
<?= $form->field($model, 'node_font_size')->radioList(PerformerOptionsForm::nodeFontSizes()) ?>

    <div class="row">
        <div class="col-xs-6">
            <fieldset>
                <legend>Ответы узлов</legend>
                <?= $form->field($model, 'variants_position')->radioList(PerformerOptionsForm::variantsPositions()) ?>

                <?= $form->field($model, 'variants_style')->dropDownList(PerformerOptionsForm::variantsStyles()) ?>
                <?= $form->field($model, 'variants_size')->dropDownList(PerformerOptionsForm::variantsSizes()) ?>
            </fieldset>
        </div>
        <div class="col-xs-6">
            <fieldset>
                <legend>Ответы групп</legend>
                <?= $form->field($model, 'group_variants_position')->radioList(PerformerOptionsForm::variantsPositions()) ?>
                <?= $form->field($model, 'group_variants_style')->dropDownList(PerformerOptionsForm::variantsStyles()) ?>
                <?= $form->field($model, 'group_variants_size')->dropDownList(PerformerOptionsForm::variantsSizes()) ?>
            </fieldset>
        </div>
    </div>
    <br/>
    <div class="small alert alert-info">
        <?= $model->extraSmallDevicesWarning() ?>
    </div>
<?php
ActiveForm::end();

Pjax::end();

Modal::end();