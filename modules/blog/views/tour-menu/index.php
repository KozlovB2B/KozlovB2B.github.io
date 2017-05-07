<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\blog\models\TourMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tour Menus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tour-menu-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tour Menu', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'division',
            'priority',
            'link_text',
            'tour_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
