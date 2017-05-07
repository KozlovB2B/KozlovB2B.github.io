<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Author */

$this->title = 'Обновить данные автора: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="author-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
