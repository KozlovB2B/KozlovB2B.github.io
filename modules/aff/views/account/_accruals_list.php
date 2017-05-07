<?php

use yii\helpers\Html;
use \app\modules\billing\models\BalanceOperations;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */
?>
<?php Pjax::begin(['id' => 'aff___account__accruals_index']); ?>

<?= GridView::widget([
    'dataProvider' => BalanceOperations::partnerAccrualsDataProvider(),
    'showOnEmpty' => false,
    'layout' => "{items}\n{pager}",
    'tableOptions' => ['class' => 'table table-striped'],
    'emptyText' => Yii::t('aff', 'You have no accruals yet'),
    'columns' => [
        'id',
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
            'format' => ['date', 'php:d.m.Y H:i:s']
        ]
    ]
]); ?>
<?php Pjax::end(); ?>