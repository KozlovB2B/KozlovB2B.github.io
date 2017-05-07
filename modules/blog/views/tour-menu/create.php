<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\TourMenu */

$this->title = 'Create Tour Menu';
$this->params['breadcrumbs'][] = ['label' => 'Tour Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tour-menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
