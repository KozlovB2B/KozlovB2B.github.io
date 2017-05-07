<?php

use yii\grid\GridView;
use app\modules\aff\models\Account;

/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */

$this->title = Yii::t('aff', 'Attracted users');
$this->params['breadcrumbs'][] = $this->title;
\Yii::$app->getModule('billing');
echo GridView::widget([
    'dataProvider' => $data_provider,
    'layout' => "{items}\n{pager}",
    'tableOptions' => ['class' => 'table table-striped'],
    'emptyText' => Yii::t('aff', 'You have no attracted users yet'),
    'columns' => [
        [
            'attribute' => 'user.username',
//            'value' => function (Account $data) {
//                return $data->user ? $data->user->splitUsername() : null;
//            }
        ],
        [
            'header' => \Yii::t("billing", "Rate"),
            'attribute' => 'billing.MenuIndicator',
            'format' => 'html'
        ],
        [
            'attribute' => 'user.created_at',
            'format' => ['date', 'php:d.m.Y H:i:s']
        ],
        [
            'attribute' => 'user.confirmed_at',
            'format' => ['date', 'php:d.m.Y H:i:s']
        ],
        [
            'header' => \Yii::t("aff", "Earned"),
            'attribute' => 'total_affiliate_earned',
            'format' => ['currency', Yii::$app->params['currency']]
        ]
    ],
]);