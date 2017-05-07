<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\daterange\DateRangePicker;
use app\modules\core\components\Division;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\UserStatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel-group" id="sales___user_stat__search_form_wrapper" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="sales___user_stat__search_form_wrapper_heading">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#sales___user_stat__search_form_wrapper" href="#sales___user_stat__search_form_wrapper_container" aria-controls="sales___user_stat__search_form_wrapper_container">
                    Поиск
                </a>
            </h4>
        </div>
        <div id="sales___user_stat__search_form_wrapper_container" class="panel-collapse collapse" role="tabpanel" aria-labelledby="sales___user_stat__search_form_wrapper_heading">
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'sales___user_stat__search_form',
                    'action' => 'index',
                    'method' => 'get',
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => false
                ]); ?>
                <div class="row">
                    <div class="col-lg-3"><?= $form->field($model, 'username') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'phone') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'email') ?></div>
                    <div class="col-lg-3">
                        <?php echo $form->field($model, 'registered_at', [
                            'options' => ['class' => 'drp-container form-group'],

                        ])->widget(DateRangePicker::classname(), [
                            'pluginOptions' => [
                                'locale' => ['applyLabel' => 'ok', 'format' => 'YYYY-MM-DD'],
                            ],
                            'presetDropdown' => true
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3"><?= $form->field($model, 'id') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'current_balance') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'scripts_created') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'current_scripts_count') ?></div>
                </div>
                <div class="row">
                    <div class="col-lg-3"><?= $form->field($model, 'current_nodes_count') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'logins_today') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'logins_yesterday') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'logins_week') ?></div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <?php echo $form->field($model, 'last_login', [
                            'options' => ['class' => 'drp-container form-group'],

                        ])->widget(DateRangePicker::classname(), [
                            'pluginOptions' => [
                                'locale' => ['applyLabel' => 'ok', 'format' => 'YYYY-MM-DD'],
                            ],
                            'presetDropdown' => true
                        ]); ?>
                    </div>
                    <div class="col-lg-3"><?= $form->field($model, 'executions_today') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'executions_yesterday') ?></div>
                    <div class="col-lg-3"><?= $form->field($model, 'executions_week') ?></div>
                </div>
                <div class="row">
                    <div class="col-lg-3"><?= $form->field($model, 'division')->dropDownList(Division::active(), ['prompt' => '-- дивизион']) ?></div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a("Импорт в Excel", Yii::$app->controller->action->id . '?excel=1&' . Yii::$app->request->queryString, ['class' => 'btn btn-success']) ?>

                    <small class="pull-right">В числовых фильтрах вы можете испоьлзовать операторы сравнения > < >= <= = <></small>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
