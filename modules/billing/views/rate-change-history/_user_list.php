<?php

use yii\helpers\Html;
use \app\modules\billing\models\BillingRateChangeHistory;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */

?>
<?php Pjax::begin(['id' => 'billing___rate_change_history__user_list']); ?>

<?= GridView::widget([
    'dataProvider' => BillingRateChangeHistory::userHistoryDataProvider(isset($user) ? $user->id : false),
    'showOnEmpty' => false,
    'layout' => "{items}\n{pager}",
    'tableOptions' => ['class' => 'table table-striped'],
    'emptyText' => Yii::t('billing', 'You have no balance operations yet'),
    'columns' => [
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
            'format' => 'datetime'
        ],
//        [
//            'attribute' => 'rate_to_data',
//            'format' => 'raw'
//        ]
    ]
]); ?>
<?php Pjax::end(); ?>