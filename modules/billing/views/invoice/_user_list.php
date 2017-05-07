<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \app\modules\billing\models\Invoice;
use rmrevin\yii\fontawesome\FA;

/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */

?>
<?php Pjax::begin(['id' => 'billing___invoice__user_list']); ?>

<?= GridView::widget([
    'dataProvider' => Invoice::userDataProvider(isset($user) ? $user->id : false),
    'showOnEmpty' => false,
    'layout' => "{items}\n{pager}",
    'tableOptions' => ['class' => 'table table-striped'],
    'emptyText' => Yii::t('billing', 'You have no invoices yet'),
    'columns' => [
        [
            'attribute' => 'id',
            'format' => 'raw',
        ],
        [
            'attribute' => 'amount',
            'format' => 'raw',
            'value' => function (Invoice $data) {
                return Yii::$app->getFormatter()->asCurrency($data->amount, "RUR");
            }
        ],
        [
            'attribute' => 'pay_for',
            'format' => 'raw',
        ],
        [
            'attribute' => 'status_id',
            'format' => 'raw',
            'value' => function (Invoice $data) {
                return $data->getStatusName();
            }
        ],
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:d.m.Y H:i:s']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, Invoice $model) {
                    return Html::a(FA::icon('eye'), '/billing/invoice/view?id=' . $model->id, ['target' => '_blank', "title" => Yii::t("billing", "View invoice")]);
                },
            ]
        ],
    ]
]); ?>
<?php Pjax::end(); ?>