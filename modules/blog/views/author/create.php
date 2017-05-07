<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Author */

$this->title = 'Добавить автора';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
