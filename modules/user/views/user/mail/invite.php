<?php
use yii\helpers\Html;

/**
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Token $token
 */

$p = "font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;";
?>
<p style="<?php echo $p; ?>">
    Привет!
</p>

<p style="<?php echo $p; ?>">
    Вы были приглашены на <?php echo Yii::$app->name ?> в качестве <?= $user->getProfile()->getName('accusative') ?>!
</p>

<p style="<?php echo $p; ?>">
    Для завершения регистрации перейдите по ссылке:
</p>
<p style="<?php echo $p; ?>">
    <?= Html::a(Html::encode($token->url), $token->url); ?>
</p>
<p style="<?php echo $p; ?>">
    Если вы не можете кликнуть по ссылке - попробуйте скопировать ее в адресную строку браузера.
</p>

<p style="<?php echo $p; ?>">
    Если вы не поняли в чем дело, напишите нам на <?php echo Yii::$app->params['mails']['help'] ?>
</p>