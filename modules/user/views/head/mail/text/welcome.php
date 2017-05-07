<?php
/**
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Token $token
 * @var bool $showPassword
 */
?>
Спасибо за то, что зарегистрировались в <?php echo Yii::$app->name ?>!
Ваш логин: <?= $user->username ?>
Пароль: <?= $user->password ?>

Для подтверждения регистрации перейдите по ссылке:
<?= $token->url ?>
Если вы не можете кликнуть по ссылке - попробуйте скопировать ее в адресную строку браузера.

Если в какой-то момент вам потребуется помощь в использовании <?php echo Yii::$app->name ?>, напишите нам на <?php echo Yii::$app->params['mails']['help'] ?>

Если вы не регистрировались ни в каком <?php echo Yii::$app->name ?> - просто проигнорируйте это сообщение.