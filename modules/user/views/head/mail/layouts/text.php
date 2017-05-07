<?php
/**
 * @var string $content main view render result
 */
?>

<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $content ?>
Команда <?= Yii::$app->name ?>
<?php $this->endBody() ?>
<?php $this->endPage() ?>