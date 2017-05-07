<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\modules\core\components\CoreAssetBundle;
use rmrevin\yii\fontawesome\AssetBundle as FontAwesomeAssetBundle;
use app\modules\api\components\ParentFrameMessengerAssetBundle;


CoreAssetBundle::register($this);
FontAwesomeAssetBundle::register($this);
ParentFrameMessengerAssetBundle::register($this);

Yii::$app->getModule('site');
Yii::$app->getModule('user');
Yii::$app->getModule('script');
Yii::$app->getModule('billing');
$this->registerJs("new ParentFrameMessenger();");
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
</head>
<body>
<?php echo $this->render('_service'); ?>
<?php $this->beginBody() ?>
<?php echo Yii::$app->getUser()->getIsGuest() ? '' : $this->render('_menu'); ?>
<div class="container-fluid">
    <?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
