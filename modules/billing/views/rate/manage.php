<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\billing\models\Rate;

/**
 * @var $this yii\web\View
 * @var $data_provider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('billing', 'Rates');
$this->params['breadcrumbs'][] = $this->title;
?>
    <p>
        <?= Html::a(Yii::t('billing', 'Create rate'), ['create'], ['class' => 'btn btn-success disabled']) ?>
    </p>

<?= GridView::widget([
    'dataProvider' => $data_provider,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        [
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function (Rate $data) {
                return $data->is_default ? $data->name . ' ' . Html::tag('span', Yii::t('billing', 'By default'), ['class' => 'label label-success', 'title' => Yii::t('billing', 'Setting by default when user activate account')]) : $data->name;
            },
        ],
        [
            'attribute' => 'monthly_fee',
            'format' => 'raw',
            'value' => function (Rate $data) {
                return Yii::$app->getFormatter()->asCurrency($data->monthly_fee, $data->currency);
            },
        ],
        'division',
        'operators_threshold',
        'executions_per_day',
        'executions_per_month',
        [
            'header' => Yii::t('billing', 'Exporting'),
            'attribute' => 'export_allowed',
            'format' => 'raw',
            'value' => function (Rate $data) {
                return Html::tag('span', Yii::t('billing', ($data->export_allowed ? 'Allowed' : 'Prohibited')), ['class' => 'label label-' . ($data->export_allowed ? 'success' : 'danger')]);
            },
        ],
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:d.m.Y']
        ],

//        ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}', 'controller' => '/script/script'],
    ],
]); ?>