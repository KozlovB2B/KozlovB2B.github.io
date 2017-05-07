<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\daterange\DateRangePicker;

/**
 * @var yii\data\ActiveDataProvider $data_provider
 * @var \app\modules\aff\models\AdEffectReport $report
 */

$this->title = Yii::t("aff", 'Advertising effectiveness report');
$this->params['breadcrumbs'][] = ['label' => Yii::t('aff', 'Affiliate program'), 'url' => '/aff'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('aff', 'Hits'), 'url' => '/aff/hit'];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin([
    'layout' => 'inline',
    'method' => 'get',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>
    <div class="row">
        <div class="col-lg-10">
            <?= Yii::t("aff", 'Period') ?>
            &nbsp;&nbsp;
            <?php echo $form->field($report, 'created_at', [
                'options' => ['class' => 'drp-container form-group'],
            ])->widget(DateRangePicker::classname(), [
                'pluginOptions' => [
                    'locale' => ['applyLabel' => 'ok'],
                ],
                'presetDropdown' => true
            ]); ?>
            <?= $form->field($report, 'registered')->dropDownList([1 => Yii::t('aff', 'Registered'), 0 => Yii::t('aff', 'Not registered')], ['prompt' => '-- ' . $report->getAttributeLabel('registered')]); ?>
            <?= $form->field($report, 'paid')->dropDownList([1 => Yii::t('aff', 'Paid'), 0 => Yii::t('aff', 'Not paid')], ['prompt' => '-- ' . $report->getAttributeLabel('paid')]); ?>
            <br/>
            <br/>
            <?= Yii::t("aff", $report->getAttributeLabel('utm_medium')) ?>
            &nbsp;&nbsp;
            <?= $form->field($report, 'utm_medium')->textInput(['placeholder' => $report->getAttributeLabel('utm_medium')]) ?>

            &nbsp;&nbsp;
            &nbsp;&nbsp;
            <?= Yii::t("aff", $report->getAttributeLabel('utm_source')) ?>
            &nbsp;&nbsp;
            <?= $form->field($report, 'utm_source')->textInput(['placeholder' => $report->getAttributeLabel('utm_source')]) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::submitButton(Yii::t("aff", 'Search'), ['class' => 'btn btn-success']) ?><br/><br/>
            <?= Html::a(Yii::t("aff", 'Excel export'), '/aff/hit/ad-effect?excel=1&' . Yii::$app->request->queryString, ['class' => 'btn btn-xs btn-primary']) ?>
        </div>
    </div>


<?php ActiveForm::end(); ?>
    <br/>
<?= GridView::widget([
    'dataProvider' => $data_provider,
    'columns' => [
        'utm_medium',
        'utm_source',
        'total_count',
        [
            'attribute' => 'earned',
            'format' => ['currency', Yii::$app->params['currency']]
        ],
        'registrations',
        'bills_total',
        'bills_paid_total'
    ],
]);