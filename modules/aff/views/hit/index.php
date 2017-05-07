<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\aff\models\HitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('aff', 'Hits');
$this->params['breadcrumbs'][] = ['label' => Yii::t('aff', 'Affiliate program'), 'url' => '/aff'];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:Y-m-d H:i:s']
        ],
        'utm_medium',
        'utm_source',
        'utm_campaign',
//        'utm_content',
//        'utm_term',
        'ip',
        'os',
        'browser',
        'ref',
        ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
    ],
]);
