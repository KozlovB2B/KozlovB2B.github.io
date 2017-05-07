<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\blog\models\Author;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <p>
        <?= Html::a('Добавить автора', ['create'], ['class' => 'btn btn-success']) ?>

        <span class="pull-right">
            <strong>Для чего нужны авторы:</strong> при создании поста можно указать автора.
            <br/>Если у поста есть автор - информация о нем будет показана внизу поста, а так же в списке постов.<br/>
            Дивизион поста и автора должны совпадать.
        </span>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => [
            [
                'attribute' => 'avatar',
                'format' => 'html',
                'value' => function (Author $data) {
                    return Html::img($data->avatar, [
                        'style' => 'width:150px;'
                    ]);
                },
            ],
            'name',
            'division',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ]
        ],
    ]); ?>
</div>
