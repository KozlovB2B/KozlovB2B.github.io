<?php
/** @var OperatorRegistrationForm $model */
/* @var app\modules\script\models\Script $script */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\modules\script\models\CallEndReason;
use app\modules\script\models\Call;

$model = new \app\modules\script\models\Call();

$form = ActiveForm::begin([
    'action' => '/script/call/end',
    'id' => "script___call__perform_form",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);

$header = "<div class='pull-left'>" .
    Html::tag("strong", Yii::t('script', 'Script')) .
    " #" .
    Html::tag("span", "", ["id" => "script___call__perform_script_id"]) .
    "&nbsp;&nbsp;&nbsp; (" . Html::tag("span", "", ["id" => "script___call__perform_script_name"]) . ")&nbsp;&nbsp;&nbsp;<br/><small>" .
    Html::tag("span", Yii::t('script', 'Current node')) .
    ": " .
    Html::tag("span", "", ["id" => "script___call__perform_current_node"]) .
    "</small></div>" .
    Html::tag("div", "", ["class" => "pull-right", "id" => "script___call__perform_form_timer"]);

Modal::begin([
    'header' => $header,
    'id' => 'script___call__perform_form_modal',
    'closeButton' => false,
    'size' => Modal::SIZE_LARGE,
//    'footer' => false,
]); ?>
<?= $form->field($model, 'script_id')->hiddenInput(["id" => "script___call__perform_form_script_id"])->label(false) ?>
<?= $form->field($model, 'script_version')->hiddenInput(["id" => "script___call__perform_form_script_version"])->label(false) ?>
<?= $form->field($model, 'start_node_id')->hiddenInput(["id" => "script___call__perform_form_start_node_id"])->label(false) ?>
<?= $form->field($model, 'end_node_id')->hiddenInput(["id" => "script___call__perform_form_end_node_id"])->label(false) ?>
<?= $form->field($model, 'started_at')->hiddenInput(["id" => "script___call__perform_form_started_at"])->label(false) ?>
<?= $form->field($model, 'ended_at')->hiddenInput(["id" => "script___call__perform_form_ended_at"])->label(false) ?>
<?= $form->field($model, 'call_history')->hiddenInput(["id" => "script___call__perform_form_call_history"])->label(false) ?>

    <div id="script___call__perform_start_screen">
        <div class="row">


            <div class="col-xs-10 col-xs-offset-1">

                <h4><?= Yii::t("script", "Dial a number, wait for an answer, then push the &laquo;Pucked up&raquo; button and prepare to say:") ?></h4>

            </div>
        </div>
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1" id="script___call__first_node"></div>
        </div>
        <div class="row text-center">
            <div class="col-xs-4 col-xs-offset-4">
                <?= Html::button(Yii::t('script', 'Picked up'), ['class' => 'btn btn-success btn-lg', 'id' => "script___call__perform_form_start_call_button"]) ?>

            </div>

        </div>
    </div>

    <div class="row" id="script___call__perform_end_screen">
        <div class="col-xs-6 col-xs-offset-3">
            <h4 class="text-center"> <?= Yii::t('script', 'Call completed!') ?></h4>
            <?php
            if (!isset($remove_save_button) || $remove_save_button !== true) {
//                $list = CallEndReason::getListForCurrentAccount();
//                $replacements = CallEndReason::getCommentReplacementsForCurrentAccount();
//                $this->registerJs("window['call'].end_reason_comment_title_replacements = " . json_encode($replacements) . ";");
//                $this->registerJs("window['call'].end_reason_comment_title_replacement_default = '" . Yii::t('script', 'Comment') . "';");

//                if (count($list)) {
//                    echo $form->field($model, 'reason_id')->dropDownList($list, ["id" => "script___call__perform_reason_id"]);
//                } ?>
                <?php echo $form->field($model, 'is_goal_reached')->dropDownList(Call::isGoalReachedVariants(), ['prompt' => '']); ?>
                <?php echo $form->field($model, 'normal_ending')->dropDownList(Call::normalEndingsVariants(), ['prompt' => '']); ?>

                <?= $form->field($model, 'comment')->textarea()->label(Html::tag("span", Yii::t('script', 'Comment'), ["id" => "script___call__perform_form_comment_label"])) ?>
                <?= Html::submitButton(Yii::t('site', 'Save'), ['class' => 'btn btn-success btn-lg pull-right', 'id' => "script___call__perform_form_save_button"]) ?>
            <?php } ?>

        </div>

    </div>

    <div id="script___call__perform_working_area" style="display: none">
        <div class="row">
            <div class="col-xs-8">
                <?php echo Yii::t('script', 'Current word') ?>
                <div id="script___call__current_node">

                </div>
            </div>
            <div class="col-xs-4">
                <?php echo Yii::t('script', 'Conversation history') ?>
                <div id="script___call__history">

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-8" id="script___call__current_edges">

            </div>
            <div class="col-xs-4" id="script___call__functions_buttons">
                <?= Html::button(Yii::t('script', 'Back'), ['class' => 'btn btn-default', 'id' => "script___call__perform_form_back_button"]) ?>
                <?= Html::button(Yii::t('script', 'End call'), ['class' => 'btn btn-success', 'id' => "script___call__perform_form_end_call"]) ?>

            </div>
        </div>
    </div>
<?php
Modal::end();
ActiveForm::end();