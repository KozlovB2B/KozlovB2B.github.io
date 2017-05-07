<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\modules\core\components\CoreAssetBundle;
use rmrevin\yii\fontawesome\AssetBundle as FontAwesomeAssetBundle;
use app\modules\site\behaviors\DefaultSeoContentBehavior;


CoreAssetBundle::register($this);
FontAwesomeAssetBundle::register($this);

$this->attachBehavior('default_seo_content', new DefaultSeoContentBehavior());

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
<!--    <link rel="stylesheet" href="/css/lumen.bootstrap.css">-->
</head>
<body>
<?php echo $this->render('_service'); ?>
<?php $this->beginBody() ?>
<div class="wrap">
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <div class="container">
        <?= $content ?>
    </div>
</div>
<?= $this->render("@app/modules/site/views/layouts/_widget") ?>
<?= $this->render("@app/modules/site/views/layouts/_metrics"); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
