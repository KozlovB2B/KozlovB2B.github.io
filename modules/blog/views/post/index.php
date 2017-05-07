<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\blog\models\Post;
use app\modules\core\components\Division;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\blog\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-2"><?= Html::a('Добавить пост', ['create'], ['class' => 'btn btn-success']) ?></div>
    <div class="col-xs-9">
        <p>
            <small>
                Пользователи видят блог на странице <a href="/blog" target="_blank">блог / новости</a> в левой колонке идут посты отсортированные по дате публикации.<br/>
                Для просмотра доступны только <strong>опубликованные</strong> посты, <strong class="text-danger">соответсвующие дивизиону пользователя</strong>.<br/>
                В правой колонке находится список тегов, отсортированный по частоте использования тега. Нажав на тег, пользователь увидит в ленте все посты, содержащие этот тег.<br/>
                <strong>Используйте теги для создания фидов.</strong> Например если вы используете тег &laquo;Новости&raquo; пользователь сможет увидеть все новости, нажав на тег &laquo;Новости&raquo;
            </small>
        </p>
    </div>
</div>

<div class="post-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => [
            'id',
            [
                'attribute' => 'division',
                'filter' => Division::active()
            ],
            [
                'header' => 'Автор',
                'attribute' => 'author.name'
            ],
            'heading',
            [
                'attribute' => 'status_id',
                'filter' => Post::getStatuses(),
                'value' => function (Post $model) {
                    return $model->getStatus();
                }
            ],
            [
                'attribute' => 'published_at',
                'format' => 'date'
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date'
            ],
            ['class' => 'yii\grid\ActionColumn']
        ],
    ]); ?>

</div>
