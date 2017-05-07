<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\site\models\MultiSessionGuard $model
 * @var string $redirect
 */
?>

<div class="container-fluid">
    <div class="col-lg-8 col-lg-offset-2">
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>

        <h1 class="text-center">
            <?php echo Yii::t('site', 'Someone already logged in with your account. If you want to perform call - terminate all other sessions.') ?>

            <br/>
            <br/>
            <?php echo Html::a(Yii::t('site', 'Yes, terminate all other sessions'), '/site/multi-session-guard/terminate-other-sessions?t=' . $model->token . '&redirect='.$redirect, ['class' => 'btn btn-primary btn-lg']) ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php echo Html::a(Yii::t('site', 'No, i will not use call function'), '/', ['class' => 'btn btn-default btn-lg']) ?>
        </h1>
    </div>
</div>