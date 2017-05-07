<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\daterange\DateRangePicker;
use app\modules\core\components\BootstrapDatePickerAsset;
use app\modules\billing\components\CashflowReport;

/**
 * @var yii\data\ActiveDataProvider $data_provider
 * @var \app\modules\billing\components\CashflowReport $report
 */


BootstrapDatePickerAsset::register($this);

$this->title = 'Отчет по списаниям';
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin([
    'layout' => 'inline',
    'method' => 'get',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>
    <div class="row">
        <div class="col-xs-4">
            <div class="input-group input-daterange" id="daterange">
                <?php echo Html::activeTextInput($report, 'from', ['class' => 'form-control']) ?>
                <span class="input-group-addon">по</span>
                <?php echo Html::activeTextInput($report, 'to', ['class' => 'form-control']) ?>
            </div>
        </div>
        <div class="col-xs-2 col-xs-offset-6">
            <?= Html::submitButton('Сформировать отчет', ['class' => 'btn btn-success']) ?><br/><br/>
            <?= Html::a('Экспорт в Excel',  '/billing/balance-operations/cashflow-report?excel=1&' . Yii::$app->request->queryString, ['class' => 'btn btn-xs btn-primary']) ?>
        </div>
    </div>
<?php $this->registerJs("
    $('#daterange').datepicker({
        autoclose: true,
        startDate: '" . $report->getMinMonth() . "',
        endDate: '" . $report->getMaxMonth() . "',
        language: 'ru',
        minViewMode: 1,
        format: 'mm.yyyy'
    });
"); ?>

<?php ActiveForm::end();


?>
    <br/>
<?php

$columns = [
    'account_id',
    'user.username',
    'userHeadManager.phone',
    [
        'attribute' => 'balance.balance',
        'header' => 'Текущий баланс',
        'format' => 'currency',
    ],
    'bankProps.inn',
    'bankProps.company_name',
    [
        'attribute' => 'total_withdraw',
        'header' => 'Всего за период',
        'format' => 'currency',
    ],
];

foreach ($report->getMonths() as $month => $date) {
    $columns[] = [
        'attribute' => $month,
        'header' => Yii::$app->getFormatter()->asDate(strtotime($date), 'MM.Y'),
        'value' => function (CashflowReport $model, $key, $index, $column) {
            $m = $column->attribute;
            if ($model->$m == 0) {
                return '';
            }

            return Yii::$app->getFormatter()->asCurrency($model->$m);
        },
    ];
}

echo GridView::widget([
    'dataProvider' => $data_provider,
    'columns' => $columns,
]);