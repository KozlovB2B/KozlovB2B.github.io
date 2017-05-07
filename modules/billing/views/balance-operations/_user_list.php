<?php

use yii\helpers\Html;
use \app\modules\billing\models\BalanceOperations;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
?>
<?php Pjax::begin(['id' => 'billing___balance_operations__user_list']); ?>

<?= GridView::widget([
    'dataProvider' => BalanceOperations::userOperationsDataProvider(isset($user) ? $user->id : false),
    'showOnEmpty' => false,
    'layout' => "{items}\n{pager}",
    'tableOptions' => ['class' => 'table table-striped'],
    'emptyText' => Yii::t('billing', 'You have no balance operations yet'),
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
                return Yii::$app->getFormatter()->asCurrency($data->amount, $data->currency);
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
            'format' => ['datetime']
        ]
    ]
]); ?>
<?php Pjax::end(); ?>