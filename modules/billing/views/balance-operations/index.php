<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\modules\billing\models\BalanceOperations;

/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */
$this->title = 'Операции с балансом';
$this->params['breadcrumbs'][] = $this->title;

?>
<p>
    <?= Html::a('Экспорт в Excel', '/billing/balance-operations/excel', ['class' => 'btn btn-success']); ?>
</p>

<div class="script-index">
    <?= GridView::widget([
        'dataProvider' => $data_provider,
        'columns' => [
            'id',
            'user.username',
            [
                'attribute' => 'is_accrual',
                'header' => false,
                'format' => 'raw',
                'value' => function (BalanceOperations $data) {
                    return Html::tag('span', Yii::t('billing', $data->is_accrual ? 'Accrual' : 'Withdrawal'), ["class" => "label " . ($data->is_accrual ? 'label-success' : 'label-primary')]);
                }
            ],
            [
                'attribute' => 'amount',
                'format' => 'raw',
                'value' => function (BalanceOperations $data) {
                    return Yii::$app->getFormatter()->asCurrency($data->amount, 'RUR');
                }
            ],
            [
                'attribute' => 'type_id',
                'format' => 'raw',
                'value' => function (BalanceOperations $data) {
                    return Html::tag('small', $data->getTypeName());
                }
            ],
            [
                'attribute' => 'comment',
                'format' => 'raw',
                'value' => function (BalanceOperations $data) {
                    return Html::tag('small', $data->comment);
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime'
            ]
        ]
    ]); ?>

</div>
