<?php

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Post */

$this->title = 'Создать пост';
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_form', [
    'model' => $model,
]);
