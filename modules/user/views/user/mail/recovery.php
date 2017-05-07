<?php
/**
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Token $token
 */

use yii\helpers\Html;

$p = "font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;";
?>
<p style="<?php echo $p; ?>">
    Привет, <?= $user->username ?>!
</p>

<p style="<?php echo $p; ?>">
    Мы получили запрос на восстановление пароля от вашего аккаунта на <?php echo Yii::$app->name ?>!
</p>

<p style="<?php echo $p; ?>">
    Для смены пароля перейдите по ссылке:
</p>
<p style="<?php echo $p; ?>">
    <?= Html::a(Html::encode($token->url), $token->url); ?>
</p>
<p style="<?php echo $p; ?>">
    Если вы не можете кликнуть по ссылке - попробуйте скопировать ее в адресную строку браузера.
</p>

<p style="<?php echo $p; ?>">
    Если в какой-то момент вам потребуется помощь в использовании <?php echo Yii::$app->name ?>, напишите нам на <?php echo Yii::$app->params['mails']['help'] ?> или создайте тикет в системе.
    Мы любим отзывы, предложения и вопросы, поэтому отвечаем достаточно быстро.
</p>

<p style="<?php echo $p; ?>">
    Если вы не делали запрос на восстановление пароля - просто проигнорируйте это сообщение.
</p>