<?php
use yii\helpers\Html;

/**
 * @var app\modules\script\models\Call $model
 * @var $this yii\web\View
 */
?>
<div class="row" id="script___call__perform_working_area" style="display: none">
    <div class="col-xs-5 text-right">
        <h3 class="script___call__perform_working_area-heading">
            <?php echo Yii::t('script', 'Variants') ?>
        </h3>
        <div class="text-right script___call__perform_working_area_func_buttons_wrapper">
            <?= Html::button(Yii::t('script', 'Back'), ['class' => 'btn btn-warning btn-xs', 'id' => "script___call__perform_form_back_button"]) ?>
        </div>
        <br/>
        <div id="script___call__current_edges"></div>
        <div id="script___call__common_cases"></div>
    </div>
    <div class="col-xs-7">
        <h3 class="script___call__perform_working_area-heading">
            <?php echo Yii::t('script', 'Current node') ?> #<span id="script___call__perform_current_node"></span>
        </h3>
        <div class="script___call__perform_working_area_func_buttons_wrapper">
            <?= Html::button(Yii::t('script', 'End call'), ['class' => 'btn btn-success btn-xs pull-right', 'id' => "script___call__perform_form_end_call"]) ?>

            <span class="" id="script___call__perform_form_timer"></span>
        </div>
        <div id="script___call__current_node"></div>
    </div>
</div>