<?php
/**
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Token $token
 */
?>
Привет, <?= $user->username ?>!

Мы получили запрос на восстановление пароля от вашего аккаунта на <?php echo Yii::$app->name ?>!

Для смены пароля перейдите по ссылке:

<?= $token->url ?>

Если вы не можете кликнуть по ссылке - попробуйте скопировать ее в адресную строку браузера.


Если в какой-то момент вам потребуется помощь в использовании <?php echo Yii::$app->name ?>, напишите нам на <?php echo Yii::$app->params['mails']['help'] ?> или создайте тикет в системе.
Мы любим отзывы, предложения и вопросы, поэтому отвечаем достаточно быстро.

Если вы не делали запрос на восстановление пароля - просто проигнорируйте это сообщение.