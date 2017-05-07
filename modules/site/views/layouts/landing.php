<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use app\modules\core\components\CoreAssetBundle;
use app\modules\site\components\PublicAssetBundle;
use app\modules\site\behaviors\DefaultSeoContentBehavior;
use yii\widgets\PjaxAsset;

CoreAssetBundle::register($this);
PublicAssetBundle::register($this);
PjaxAsset::register($this);

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
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <?php $this->beginBody() ?>
    <body>
    <div class="landing-wrapper" id="start">
        <div id="landing">
            <?php echo $content ?>
        </div>
        <div class="container-fluid">
            <?php echo $this->render("_public_footer"); ?>
        </div>
    </div>
    <?= $this->render("@app/modules/site/views/layouts/_widget") ?>
    <?= $this->render("@app/modules/site/views/layouts/_metrics"); ?>
    </body>
    <?php $this->endBody() ?>
    </html>
<?php $this->endPage() ?>