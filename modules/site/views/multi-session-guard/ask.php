<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\site\models\MultiSessionGuard $model
 */
if ($model->isHeadManager()) {
    $message = Yii::t('site', 'Perhaps another user now works with your account. Your authentication will make it impossible to maintain the results of his work. Want to get started?');
} else {
    $message = Yii::t('site', 'Perhaps another operator is already running under your login. Your input will throw out the user from the system. Want to get started?');
}
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
            <?php echo $message ?>
            <br/>
            <br/>
            <?php echo Html::a(Yii::t('site', 'Yes, throw it out of the system'), '/site/multi-session-guard/use?t=' . $model->token, ['class' => 'btn btn-primary btn-lg']) ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php echo Html::a(Yii::t('site', 'No, enter a different username'), '/', ['class' => 'btn btn-default btn-lg']) ?>
        </h1>
    </div>
</div>