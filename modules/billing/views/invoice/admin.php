<?php
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use app\modules\user\models\UserHeadManager;
use app\modules\billing\models\Invoice;

/**
 * @var yii\data\ActiveDataProvider $data_provider
 * @var \app\modules\billing\models\InvoiceSearch $search
 */


$this->title = 'Выписанные счета';
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin([
    'id' => 'billing___invoice__admin_search_form',
    'action' => '/billing/invoice/admin',
    'layout' => 'inline',
    'method' => 'get',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>

<?= $form->field($search, 'id')->textInput(['placeholder' => 'ID']) ?>

<?= $form->field($search, 'username')->textInput(['placeholder' => 'логин']) ?>

<?= $form->field($search, 'amount')->textInput(['placeholder' => 'сумма']) ?>

<?= $form->field($search, 'status_id')->dropDownList(Invoice::getStatuses()) ?>

<?= DatePicker::widget([
    'model' => $search,
    'attribute' => 'created_at',
    'dateFormat' => 'php:Y-m-d',
    'options' => [
        'placeholder' => 'дата',
        'class' => 'form-control'
    ]
]) ?>

<?= Html::submitButton('Поиск', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
    <br/>
<?= GridView::widget([
    'dataProvider' => $data_provider,
    'columns' => [
        'id',
        'name',
        'user.username',
        [
            'attribute' => 'amount',
            'value' => function (Invoice $model) {
                return Yii::$app->getFormatter()->asCurrency($model->amount, "RUR");
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'status_id',
            'value' => function (Invoice $model) {

                if ($model->status_id == Invoice::INVOICE_STATUS_IN_PROCESS) {
                    return $model->getStatusName() . '&nbsp;&nbsp;&nbsp;' . Html::a('Подтвердить', ['confirm', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success',
                        'data-method' => 'post',
                        'data-confirm' => 'Хотите отметить счет как оплаченный?',
                    ]) . ' или ' . Html::a('Отменить', ['decline', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-danger',
                        'data-method' => 'post',
                        'data-confirm' => 'Хотите отменить счет?',
                    ]);
                }

                return $model->getStatusName();
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'created_at',
            'value' => function (Invoice $model) {
                return Yii::$app->getFormatter()->asDate($model->created_at);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
        ],
    ],
]);