<?php
/**
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Token $token
 */
?>
Привет!

Вы были приглашены на <?php echo Yii::$app->name ?> в качестве <?= $user->getProfile()->getName('accusative') ?>!

Для завершения регистрации перейдите по ссылке:
<?= $token->url ?>
Если вы не можете кликнуть по ссылке - попробуйте скопировать ее в адресную строку браузера.

Если вы не поняли в чем дело, напишите нам на <?php echo Yii::$app->params['mails']['help'] ?>