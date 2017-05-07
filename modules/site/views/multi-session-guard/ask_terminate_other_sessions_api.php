<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\site\models\MultiSessionGuard $model
 * @var string $redirect
 * @var string $decline_url
 */
?>

<div class="col-xs-10 col-xs-offset-1 col-lg-8 col-lg-offset-2">
    <h4 class="text-center">
        <?php echo Yii::t('site', 'Someone already logged in with your account. If you want to perform call - terminate all other sessions.') ?>
    </h4>

</div>
<br/>
<br/>
<br/>
<br/>
<div class="text-center">
    <?php echo Html::a(Yii::t('site', 'Yes, terminate all other sessions'), '/site/multi-session-guard/terminate-other-sessions?t=' . $model->token . '&redirect=' . $redirect, ['class' => 'btn btn-primary']) ?>
    <br/>
    <br/>
    <?php echo Html::a(Yii::t('site', 'No, i will not use call function'), $decline_url, ['class' => 'btn btn-default']) ?>
</div>