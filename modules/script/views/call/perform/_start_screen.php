<?php
use yii\helpers\Html;

?>
<div class="row" id="script___call__perform_start_screen">
    <div class="col-xs-6 col-xs-offset-3">
        <div class="row">
            <h4><?= Yii::t("script", "Dial a number, wait for an answer, then push the {button} button and prepare to say:", ['button' => Html::button(Yii::t('script', 'Picked up'), ['class' => 'btn btn-primary btn-sm script___call__perform_form_start_call_button'])]) ?></h4>

        </div>
        <div class="row" id="script___call__first_node"></div>
        <div class="row text-center">
            <br/>

            <br/>
            <?php echo Html::button(Yii::t('script', 'Picked up'), ['class' => 'btn btn-primary btn-lg script___call__perform_form_start_call_button']) ?>
        </div>

    </div>
</div>