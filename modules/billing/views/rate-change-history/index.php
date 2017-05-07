<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\modules\billing\models\BillingRateChangeHistory;

/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */
$this->title = 'История смены тарифов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="script-index">
    <?= GridView::widget([
        'dataProvider' => $data_provider,
        'columns' => [
            'id',
            'user.username',
            [
                'attribute' => 'rate_from',
                'format' => 'raw',
                'value' => function (BillingRateChangeHistory $data) {
                    return $data->rateFrom ? $data->rateFrom->name : '&nbsp;';
                }
            ],
            [
                'attribute' => 'rate_to',
                'format' => 'raw',
                'value' => function (BillingRateChangeHistory $data) {
                    return $data->rateTo ? $data->rateTo->name : null;
                }
            ],
            [
                'attribute' => 'comment',
                'format' => 'raw',
                'value' => function (BillingRateChangeHistory $data) {
                    return Html::tag('small', $data->comment);
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime']
            ]
        ]
    ]); ?>

</div>
