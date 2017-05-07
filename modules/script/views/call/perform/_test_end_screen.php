<?php
use yii\helpers\Html;
use app\modules\script\models\Call;

/**
 * @var app\modules\script\models\Call $model
 * @var $this yii\web\View
 */
?>
<div class="row" id="script___call__perform_end_screen" style="display: none">
    <div class="col-lg-6 col-lg-offset-3 text-center">
        <h4 class="text-center"> <?= Yii::t('script', 'Conversation complete!') ?></h4>
        <br/>
        <br/>
        <br/>
        <?= Html::button(Yii::t('script', 'Start a new test call with the same script'), ['class' => 'btn btn-primary', 'onclick' => 'window.location.reload()']) ?>
    </div>
</div>

