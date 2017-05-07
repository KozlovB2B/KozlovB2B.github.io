<?php

use yii\helpers\Html;
use \app\modules\script\models\ar\Script;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\script\models\Script */

?>

<?php $form = ActiveForm::begin([
    'id' => "script___script___update_form",
    'layout' => "inline",
    'enableAjaxValidation' => false
]); ?>
    <div class="hide">
        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'max_node')->hiddenInput(["id" => "script___script___update_form_script_max_node"])->label(false) ?>
        <?= $form->field($model, 'max_edge')->hiddenInput(["id" => "script___script___update_form_script_max_edge"])->label(false) ?>
        <?= $form->field($model, 'data_json')->hiddenInput(["id" => "script___script___update_form_script_data"])->label(false) ?>
        <?= Html::hiddenInput('is_test', 0, ["id" => "script___script___update_form_is_test"]) ?>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <?= $form->field($model, 'name')->textInput(['id' => 'script___script___form_name', 'placeholder' => $model->getAttributeLabel('name')])->label(false) ?>
        </div>
        <div class="col-xs-8 text-right">
            <?= $form->field($model, 'start_node_id')->dropDownList($model->getNodesForStartNodeSelect(), ["id" => "script___script___update_form_start_node_id", "prompt" => "-- " . $model->getAttributeLabel('start_node_id')])->label(false) ?>
            <?= $form->field($model, 'operator_interface_type_id')->dropDownList(Script::operatorInterfaceTypes(), ["prompt" => "-- " . $model->getAttributeLabel('operator_interface_type_id')])->label(false) ?>
            <?= $form->field($model, 'status_id')->dropDownList(Script::getStatuses(), ["prompt" => "-- " . $model->getAttributeLabel('status_id')])->label(false) ?>
            &nbsp;
            &nbsp;
            &nbsp;
            <?= Html::submitButton(FA::icon('save') . " &nbsp; " . Yii::t('script', 'Save script'), ['class' => 'btn btn-success btn-sm', 'id' => 'script___script___update_form_button']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>