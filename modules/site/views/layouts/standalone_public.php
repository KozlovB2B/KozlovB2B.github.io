<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\modules\site\components\CompanyStyleAssetBundle;
use yii\bootstrap\BootstrapAsset;
BootstrapAsset::register($this);
//\app\modules\site\components\assets\V2DesignAsset::register($this);
\app\modules\core\components\CoreAssetBundle::register($this);
$company_style = CompanyStyleAssetBundle::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= $company_style->faviconHtml() ?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<body>
<div id="main-wrapper">
    <div class="text-center">
        <img src="<?php echo $company_style->baseUrl . '/logo/favicon-96x96.png' ?>" style="margin-top: 35px; margin-bottom: 35px">
    </div>

    <div class="container">
        <div class="row">
            <div class="
            col-lg-4 col-lg-offset-4
            col-md-6 col-md-offset-3
            col-sm-6 col-sm-offset-3
            col-xs-8 col-xs-offset-2">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<br/>
<br/>
<br/>
<?= $this->render("@app/modules/site/views/layouts/_widget") ?>
<?= $this->render("@app/modules/site/views/layouts/_metrics"); ?>

</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>

