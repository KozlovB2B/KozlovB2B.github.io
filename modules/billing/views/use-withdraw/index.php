<?php
use yii\grid\GridView;
use \app\modules\billing\models\UseWithdraw;

/**
 * @var $this yii\web\View
 * @var $data_provider yii\data\ActiveDataProvider
 */

$this->title = 'Процедуры списания за использование сервиса';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider' => $data_provider,
    'columns' => [
        'id',
        'accounts',
        [
            'attribute' => 'total',
            'format' => 'raw',
            'value' => function (UseWithdraw $data) {
                return Yii::$app->getFormatter()->asCurrency($data->total, 'RUR');
            }
        ],
        [
            'attribute' => 'errors',
            'format' => 'raw',
            'value' => function (UseWithdraw $data) {
                return $data->errors ? nl2br($data->errors) : '&nbsp;';
            }
        ],
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:d.m.Y H:i:s']
        ]
    ]
]);