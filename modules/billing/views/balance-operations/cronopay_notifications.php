<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */

$this->title = 'Нотификации Chronopay';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="script-index">
    <?= GridView::widget([
        'dataProvider' => $data_provider,
        'columns' => [
//            'id',
            'transaction_id',
            'transaction_type',
            'merchant_uid',
            'user.username',
            'customer_id',
            'total',
            'currency',
            'date',
            'time',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ]
        ],
    ]); ?>

</div>
