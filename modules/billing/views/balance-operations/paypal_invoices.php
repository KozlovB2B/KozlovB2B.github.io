<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */

$this->title = 'Инвойсы PayPal';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
        'dataProvider' => $data_provider,
        'columns' => [
            'id',
            'pay_pal_transaction',
            'user_id',
            'amount',
            'currency',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],
            [
                'attribute' => 'paid_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],
            [
                'attribute' => 'cancelled_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ]
        ],
    ]);

