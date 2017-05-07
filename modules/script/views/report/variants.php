<?php
use yii\grid\GridView;
use yii\helpers\Html;
use \app\modules\script\models\Call;
use app\modules\script\components\assets\VariantsReportAsset;
use yii\bootstrap\ActiveForm;

use yii\helpers\ArrayHelper;
use \app\modules\script\models\ar\Script;
use kartik\daterange\DateRangePicker;
use dosamigos\selectize\SelectizeDropDownList;
use app\modules\script\components\VariantsReport;
use app\modules\script\models\ar\Node;

/**
 * @var yii\data\ActiveDataProvider $data_provider
 * @var \app\modules\script\components\VariantsReport $report
 */

$this->title = Yii::t("script", 'Variants report');
$this->params['breadcrumbs'][] = $this->title;

VariantsReportAsset::register($this);

$this->registerJs("window['variants_report'] = new VariantsReport();");

$total_count = VariantsReport::total($data_provider->models, 'total_count');

$form = ActiveForm::begin([
    'id' => 'script___report__variants_search_form',
    'layout' => 'inline',
    'method' => 'get',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>
    <div class="row">
        <div class="col-lg-10">

            <?= $form->field($report, 'script_id')->dropDownList(Script::dropDownData(), ['id' => 'script___variants_report__script_id', 'prompt' => '-- ' . $report->getAttributeLabel('script_id')]); ?>

            <?= $form->field($report, 'node_id')->dropDownList($report->script_id ? Node::dropDownData($report->script_id) : [], ['id' => 'script___variants_report__node_id', 'prompt' => '-- ' . $report->getAttributeLabel('node_id')]); ?>

            <br/>
            <br/>

            Фильтровать звонки

            <?= $form->field($report, 'is_goal_reached')->dropDownList(Call::isGoalReachedVariants(), ['prompt' => '-- ' . $report->getAttributeLabel('is_goal_reached')]); ?>

            <?= $form->field($report, 'normal_ending')->dropDownList(Call::normalEndingsVariants(), ['prompt' => '-- ' . $report->getAttributeLabel('normal_ending')]); ?>


            <?= Yii::t("script", 'Period') ?>
            &nbsp;&nbsp;

            <?php echo $form->field($report, 'started_at', [
                'options' => ['class' => 'drp-container form-group'],

            ])->widget(DateRangePicker::classname(), [
                'pluginOptions' => [
                    'locale' => ['applyLabel' => 'ok'],
                ],
                'presetDropdown' => true
            ]); ?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?= Yii::t("script", 'Duration (seconds)') ?>
            &nbsp;&nbsp;
            <?= $form->field($report, 'duration_from')->textInput(['style' => 'width:69px', 'placeholder' => Yii::t("script", 'from'), 'class' => 'form-control']) ?>
            &mdash;
            <?= $form->field($report, 'duration_to')->textInput(['style' => 'width:69px', 'placeholder' => Yii::t("script", 'to'), 'class' => 'form-control']) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::submitButton(Yii::t("script", 'Get report'), ['class' => 'btn btn-success']) ?><br/><br/>
        </div>
    </div>


<?php ActiveForm::end(); ?>
    <br/>
<?php

if ($report->node_id) {
    echo GridView::widget([
        'dataProvider' => $data_provider,
        'columns' => [
            [
                'header' => Yii::t("script", "Variant"),
                'value' => function (VariantsReport $model) {
                    $variant = \app\modules\script\models\ar\Variant::findOne($model->variant_name);
                    if($variant){
                        return $variant->content;
                    }
                },
//                'footer' => Yii::t("script", "Total"),
            ],
            [
                'attribute' => 'total_count',
                'value' => function (VariantsReport $model) {
                    return $model->total_count;
                },
                'format' => 'raw',
//                'footer' => $total_count,
            ],

        ],
    ]);
} else {
    echo 'Выберите узел, по которому вы хотели бы просмотреть статистику.';
}


