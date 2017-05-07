<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\TourMenu */

$this->title = 'Update Tour Menu: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tour Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tour-menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
