<?php
use yii\helpers\Html;
use rmrevin\yii\fontawesome\FA;
use app\modules\script\models\Call;

use app\modules\script\components\AssetBundle;
use app\modules\script\components\Wysihtml5AssetBundle;
use app\modules\core\components\CoreAssetBundle;

use rmrevin\yii\fontawesome\AssetBundle as FontAwesomeAssetBundle;
use yii\bootstrap\Nav;
use app\modules\billing\models\Account as BillingAccount;
use kartik\typeahead\TypeaheadAsset;
use yii\bootstrap\ActiveForm;
use app\modules\script\models\ar\Script;
use dosamigos\selectize\SelectizeAsset;


FontAwesomeAssetBundle::register($this);
CoreAssetBundle::register($this);
Wysihtml5AssetBundle::register($this);
TypeaheadAsset::register($this);
SelectizeAsset::register($this);
AssetBundle::register($this);

$this->registerJs("window['app']['loading_message'] = '" . Yii::t('site', 'Loading') . "'");
$this->registerJs("window['app']['saving_message'] = '" . Yii::t('site', 'Saving') . "'");
\Yii::$app->getModule('aff');


/* @var $model app\modules\script\models\Script */
/* @var integer $focus_node Node to be focused */

$data_json = $model->data_json ? $model->data_json : "{}";
$this->registerJs("window['designer'] = new ScriptDesigner(" . $model->id . ", " . $data_json . ", " . json_encode(Call::getStages()) . ", " . ($focus_node ? $focus_node : 'null') .  ");");
$this->registerJs("window['common_cases_redactor'] = new CommonCasesRedactor(" . ($model->common_cases ? $model->common_cases : 'null') . ");");
$this->registerJs("\$(function () {\$('[data-toggle=tooltip]').tooltip()})");
?>

<div id="script___designer__main_container">
    <div class="row jtk-demo-main">
        <div class="col-xs-2" id="editor___toolbar_functions">
            <?php $form = ActiveForm::begin([
//                'action' => '/script/script/update?id=' . $model->id,
                'id' => "script___script___update_form",
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
            ]);
            //            [ 'class' => 'form-group form-group-sm']
            ?>
            <?= Html::submitButton(FA::icon('save') . " &nbsp; " . Yii::t('script', 'Save script'), ['class' => 'btn btn-success btn-sm', 'id' => 'script___script___update_form_button']) ?>

            <div class="btn btn-primary btn-sm" id="script___designer__add_node_by_drop" style="z-index: 5000;" title=" <?= Yii::t('script', 'You can drag and drop where you wand to be a node') ?>">
                <?= FA::icon('plus'); ?>
                &nbsp;
                <?= Yii::t('script', 'Add a node') ?>
            </div>
            <br/>
            <div class="btn btn-primary btn-sm" id="script___designer__function_fit">
                <?= FA::icon('object-group'); ?>
                &nbsp;
                <?= Yii::t('script', 'Fit screen') ?>
            </div>
            <br/>
            <div class="btn btn-primary btn-sm" id="script___designer__try_script">
                <?= FA::icon('phone'); ?>
                &nbsp;
                <?= Yii::t('script', 'Try a call') ?>
            </div>
            <br/>
            <div class="btn btn-primary btn-sm" data-toggle="modal" data-target="#script___script___common_cases_modal">
                <?= FA::icon('list'); ?>
                &nbsp;
                <?= Yii::t('script', 'Common cases') ?>
            </div>


            <?= $form->field($model, 'name')->textInput(['class' => 'form-control input-sm']) ?>
            <?= $form->field($model, 'operator_interface_type_id')->dropDownList(Script::operatorInterfaceTypes(), ['class' => 'form-control input-sm']) ?>
            <?= $form->field($model, 'status_id')->dropDownList(Script::getStatuses(), ['class' => 'form-control input-sm']) ?>
            <?= $form->field($model, 'start_node_id')->dropDownList($model->getNodesForStartNodeSelect(), ["id" => "script___script___update_form_start_node_id", "prompt" => "", 'class' => 'form-control input-sm']) ?>

            <div id="editor___toolbar_tools_search">
                <?= $form->field($model, 'editor___toolbar_tools_search_input')->textInput(['class' => 'form-control input-sm', "id" => "editor___toolbar_tools_search_input", "placeholder" => $model->getAttributeLabel('editor___toolbar_tools_search_input')]) ?>
            </div>

            <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'max_node')->hiddenInput(["id" => "script___script___update_form_script_max_node"])->label(false) ?>
            <?= $form->field($model, 'max_edge')->hiddenInput(["id" => "script___script___update_form_script_max_edge"])->label(false) ?>
            <?= $form->field($model, 'data_json')->hiddenInput(["id" => "script___script___update_form_script_data"])->label(false) ?>
            <?= $form->field($model, 'common_cases')->hiddenInput(["id" => "script___script___update_form_common_cases"])->label(false) ?>
            <?= Html::hiddenInput('is_test', 0, ["id" => "script___script___update_form_is_test"]) ?>


            <?php ActiveForm::end(); ?>

        </div>
        <div class="col-xs-10">
            <div id="script___designer__canvas">
                <div id="script___designer__miniview"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->render("_templates"); ?>
<div class="jtk-demo-dataset"></div>
<?= $this->render("@app/modules/script/views/edge/_create_form_modal", ["script" => $model]); ?>
<?= $this->render("@app/modules/script/views/edge/_update_form_modal", ["script" => $model]); ?>
<?= $this->render("@app/modules/script/views/node/_create_form_modal"); ?>
<?= $this->render("@app/modules/script/views/node/_update_form_modal"); ?>
<?= $this->render("_try_call_modal"); ?>
<?= $this->render("_common_cases_modal"); ?>


