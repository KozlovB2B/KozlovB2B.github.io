<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\modules\site\components\PublicAssetBundle;
use rmrevin\yii\fontawesome\AssetBundle as FontAwesomeAssetBundle;
use app\modules\site\behaviors\DefaultSeoContentBehavior;

PublicAssetBundle::register($this);
FontAwesomeAssetBundle::register($this);

$this->attachBehavior('default_seo_content', new DefaultSeoContentBehavior());

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <?= $this->render("_favicon"); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<body>
<div class="container">
    <?= $this->render("_public_menu"); ?>
    <?= $content ?>
    <?= $this->render("_public_footer"); ?>
</div>
<?= $this->render("_metrics"); ?>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
