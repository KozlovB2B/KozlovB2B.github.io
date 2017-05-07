<?php

/**
 * @var app\modules\script\models\SipAccount $sip
 * @var $this yii\web\View
 */

use app\modules\script\components\SipCallAssetBundle;

SipCallAssetBundle::register($this);

$this->registerJs("var sipCredentials = " . json_encode($sip->getAttributes()) . ";", \yii\web\View::POS_BEGIN);

?>

<div class="row" id="script___call__perform_sip_control">
    <div class="col-xs-6 col-xs-offset-3">
        <label style="width: 100%;"  id="txtRegStatus"></label>
        <label style="width: 100%;"  id="txtCallStatus"></label>
        <div class="row">
            <div class="form-inline">
                <div class="form-group">
                    <input type="email" class="form-control" id="script___call__perform_sip_call_to" placeholder="+79132225566">
                </div>

                <input type="button" id="btnCall"  class="btn btn-success" value="<?php echo Yii::t('script', 'Call') ?>" onclick="sipCall('call-audio');"  disabled="">
                <input type="button" id="btnHangUp"  class="btn btn-primary" value="<?php echo Yii::t('script', 'Hangup') ?>" onclick="sipHangUp();" disabled="">
            </div>
        </div>
        <div class="row">
            <div id="divCallOptions" class="call-options" style="opacity: 0; margin-top: 0px;">
                <input type="button" class="btn" style="" id="btnMute" value="<?php echo Yii::t('script', 'Mute') ?>" onclick="sipToggleMute();"> &nbsp;
                <input type="button" class="btn" style="" id="btnHoldResume" value="<?php echo Yii::t('script', 'Hold') ?>" onclick="sipToggleHoldResume();"> &nbsp;
                <input type="button" class="btn" style="" id="btnTransfer" value="<?php echo Yii::t('script', 'Transfer') ?>" onclick="sipTransfer();">
            </div>
        </div>
    </div>

    <audio id="audio_remote" autoplay="autoplay"> </audio>
    <audio id="ringtone" loop="" src="/static/sounds/ringtone.wav"> </audio>
    <audio id="ringbacktone" loop="" src="/static/sounds/ringbacktone.wav"> </audio>
<!--    <audio id="dtmfTone" src="sounds/dtmf.wav"> </audio>-->
</div>