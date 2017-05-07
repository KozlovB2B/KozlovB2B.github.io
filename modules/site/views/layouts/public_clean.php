<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\modules\site\components\PublicAssetBundle;

PublicAssetBundle::register($this);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?php echo Yii::t('site', 'Sales Script PROMPTER -  software and templates for cold calling and incoming phone calls') ?></title>
    <meta name="description" content="<?php echo Yii::t('site', "Рowerful software engine will make your phone calling process easy & faultless. Get ready to use templates or develop your own sales scripts >>>>") ?>">
    <meta name="keywords" content="<?php echo Yii::t('site', "sales script software,  sales script, cold calling scripts, samples, templates, phone call scripts") ?>">
    <?= $this->render("_favicon"); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<body>
<div class="container">
    <?= $content ?>
</div>
<?= $this->render("_metrics"); ?>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
