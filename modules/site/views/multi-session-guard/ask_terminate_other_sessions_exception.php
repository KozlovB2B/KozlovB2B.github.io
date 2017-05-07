<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\site\models\MultiSessionGuard $model
 */
?>

<h2 class="text-center">
    <?php echo Yii::t('site', 'Someone already logged in with your account. If you want to perform call - terminate all other sessions.') ?>
    <br/>
    <br/>
    <?php echo Html::a(Yii::t('site', 'Yes, terminate all other sessions'), '/site/multi-session-guard/terminate-other-sessions-ajax?t=' . $model->token, ['class' => 'btn btn-primary btn-lg guard-terminate-sessions']) ?>
    <br/>
    <br/>
    <?php echo Html::a(Yii::t('site', 'No, i will not use call function'), '#', ['class' => 'btn btn-default btn-lg']) ?>
</h2>