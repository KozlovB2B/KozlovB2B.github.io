<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\script\models\Call;

/**
 * @var app\modules\script\models\Call $model
 * @var $this yii\web\View
 */

$form = ActiveForm::begin([
'action' => '/script/call/end',
'id' => "script___call__perform_form",
'enableAjaxValidation' => false,
'enableClientValidation' => false,
    'options' => [
        'enctype' => 'multipart/form-data',
    ]
]); ?>

<?= $form->field($model, 'script_id')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'script_version')->hiddenInput(["id" => "script___call__perform_form_script_version"])->label(false) ?>
<?= $form->field($model, 'start_node_id')->hiddenInput(["id" => "script___call__perform_form_start_node_id"])->label(false) ?>
<?= $form->field($model, 'end_node_id')->hiddenInput(["id" => "script___call__perform_form_end_node_id"])->label(false) ?>
<?= $form->field($model, 'started_at')->hiddenInput(["id" => "script___call__perform_form_started_at"])->label(false) ?>
<?= $form->field($model, 'ended_at')->hiddenInput(["id" => "script___call__perform_form_ended_at"])->label(false) ?>
<?= $form->field($model, 'call_history')->hiddenInput(["id" => "script___call__perform_form_call_history"])->label(false) ?>

<div class="row" id="script___call__perform_end_screen" style="display: none">
    <div class="col-xs-6 col-xs-offset-3">
        <h4 class="text-center"> <?= Yii::t('script', 'Call completed!') ?></h4>
        <?php echo $form->field($model, 'is_goal_reached')->dropDownList(Call::isGoalReachedVariants(), ['prompt' => '',"id" => "script___call__perform_form_is_goal_reached"]); ?>
        <?php echo $form->field($model, 'normal_ending')->dropDownList(Call::normalEndingsVariants(), ['prompt' => '',"id" => "script___call__perform_form_normal_ending"]); ?>

        <?= $form->field($model, 'comment')->textarea(['id'=> "script___call__perform_form_comment"])->label(Html::tag("span", Yii::t('script', 'Comment'), ["id" => "script___call__perform_form_comment_label"])) ?>
        <?= Html::submitButton(Yii::t('site', 'Save'), ['class' => 'btn btn-success btn-lg pull-right', 'id' => "script___call__perform_form_save_button"]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>