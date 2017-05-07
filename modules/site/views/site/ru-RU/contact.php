<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('site', 'Contact us');
echo Html::tag('div', Html::tag('h1', $this->title), ['class' => 'public-page-heading']);
?>
<div class="row">
    <div class="col-xs-4 col-xs-offset-4">
        <p>
            <strong>Телефон:</strong> <?php echo Yii::$app->params['phone'] ?>
        </p>
        <p>
            <strong>E-mail:</strong> <?php echo Yii::$app->params['supportEmail'] ?>
        </p>
    </div>
</div>
