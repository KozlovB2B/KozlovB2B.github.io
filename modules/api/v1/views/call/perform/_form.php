<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\script\models\Call;
use app\modules\core\components\Url;

/**
 * @var app\modules\script\models\Call $model
 * @var $this yii\web\View
 * @var string $action
 */

$form = ActiveForm::begin([
    'action' => $action,
    'id' => "script___call__perform_form",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>

<?= $form->field($model, 'script_id')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'api_user')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'script_version')->hiddenInput(["id" => "script___call__perform_form_script_version"])->label(false) ?>
<?= $form->field($model, 'start_node_id')->hiddenInput(["id" => "script___call__perform_form_start_node_id"])->label(false) ?>
<?= $form->field($model, 'end_node_id')->hiddenInput(["id" => "script___call__perform_form_end_node_id"])->label(false) ?>
<?= $form->field($model, 'started_at')->hiddenInput(["id" => "script___call__perform_form_started_at"])->label(false) ?>
<?= $form->field($model, 'ended_at')->hiddenInput(["id" => "script___call__perform_form_ended_at"])->label(false) ?>
<?= $form->field($model, 'call_history')->hiddenInput(["id" => "script___call__perform_form_call_history"])->label(false) ?>
<?= $form->field($model, 'perform_page')->hiddenInput(["id" => "script___call__perform_form_perform_page"])->label(false) ?>

    <div class="row" id="script___call__perform_end_screen" style="display: none">
        <div class="col-xs-10 col-xs-offset-1 col-lg-6 col-lg-offset-3">
            <div class="row">
                <div class="col-xs-6">
                    <?php echo $form->field($model, 'is_goal_reached')->dropDownList(Call::isGoalReachedVariants(), ['prompt' => '',"id" => "script___call__perform_form_is_goal_reached"]); ?>
                </div>
                <div class="col-xs-6">
                    <?php echo $form->field($model, 'normal_ending')->dropDownList(Call::normalEndingsVariants(), ['prompt' => '',"id" => "script___call__perform_form_normal_ending"]); ?>
                </div>
            </div>

            <?= $form->field($model, 'comment')->textarea()->label(Html::tag("span", Yii::t('script', 'Comment'), ["id" => "script___call__perform_form_comment_label"])) ?>
            <?= Html::submitButton(Yii::t('site', 'Save'), ['class' => 'btn btn-success pull-right', 'id' => "script___call__perform_form_save_button"]) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>