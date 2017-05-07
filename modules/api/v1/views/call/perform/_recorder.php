<?php
/**
 * @var $this yii\web\View
 */
use app\modules\script\components\RecorderAssetBundle;

RecorderAssetBundle::register($this);
?>

<div class="container-fluid">
    <div class="row" id="script___call__perform_recorder_control">
        <div class="col-xs-12">
            <canvas id="analyser" style="width: 100%; height: 40px;"></canvas>
        </div>
    </div>
    <div class="text-center" id="script___call__perform_recorder_status">
        Разговор будет записан.
    </div>
</div>