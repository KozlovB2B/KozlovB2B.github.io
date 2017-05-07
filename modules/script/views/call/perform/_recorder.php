<?php
/**
 * @var $this yii\web\View
 */
use app\modules\script\components\RecorderAssetBundle;

RecorderAssetBundle::register($this);

//$this->registerJs("var sipCredentials = " . json_encode($sip->getAttributes()) . ";", \yii\web\View::POS_BEGIN);

?>

<div class="container">
    <div class="row" id="script___call__perform_recorder_control">
        <div class="col-xs-6 col-xs-offset-3">
            <canvas id="analyser" style="width: 100%; height: 40px;"></canvas>
        </div>
    </div>
    <div class="text-center" id="script___call__perform_recorder_status">
        Разговор будет записан.
    </div>
</div>