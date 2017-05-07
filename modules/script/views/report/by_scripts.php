<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\script\components\ByScriptsReport;

/**
 * @var yii\data\ActiveDataProvider $data_provider
 * @var \app\modules\script\components\ByScriptsReport $report
 */

$this->title = Yii::t("script", 'By scripts report');
$this->params['breadcrumbs'][] = $this->title;

echo Yii::$app->controller->renderPartial('_filter', ['report' => $report, 'url' => '/script/report/by-scripts']);

$total_count = ByScriptsReport::total($data_provider->models, 'total_count');
$show_footer = $total_count > 0;
$total_goal_reached = ByScriptsReport::total($data_provider->models, 'goal_reached');
$total_goal_not_reached = ByScriptsReport::total($data_provider->models, 'goal_not_reached');
$total_script_worked = ByScriptsReport::total($data_provider->models, 'script_worked');
$total_script_broken = ByScriptsReport::total($data_provider->models, 'script_broken');

?>
    <br/>
<?= GridView::widget([
    'dataProvider' => $data_provider,
    'showFooter' => $show_footer,
    'columns' => [
        [
            'header' => \Yii::t("script", "Script"),
            'attribute' => 'script.name',
            'footer' => \Yii::t("script", "Total"),
        ],
        [
            'attribute' => 'total_count',
            'value' => function (ByScriptsReport $model) {
                return Html::a($model->total_count, ByScriptsReport::getByCallsReportLink($model->script_id), ["target" => "blank"]);
            },
            'format' => 'raw',
            'footer' => $show_footer ? Html::a($total_count, ByScriptsReport::getByCallsReportLink(), ["target" => "blank"]) : null,
        ],
        [
            'attribute' => 'goal_reached',
            'value' => function (ByScriptsReport $model) {
                return Html::a($model->goal_reached, ByScriptsReport::getByCallsReportLink($model->script_id, 1), ["target" => "blank"]);
            },
            'format' => 'raw',
            'footer' => $show_footer && $total_goal_reached > 0 ? Html::a($total_goal_reached, ByScriptsReport::getByCallsReportLink(null, 1), ["target" => "blank"]) . ' (' . (Yii::$app->formatter->asPercent($total_goal_reached / $total_count)) . ')' : null,
        ],
        [
            'attribute' => 'goal_not_reached',
            'value' => function (ByScriptsReport $model) {
                return Html::a($model->goal_not_reached, ByScriptsReport::getByCallsReportLink($model->script_id, 0), ["target" => "blank"]);
            },
            'format' => 'raw',
            'footer' => $show_footer && $total_goal_not_reached > 0 ? Html::a($total_goal_not_reached, ByScriptsReport::getByCallsReportLink(null, 0), ["target" => "blank"]) . ' (' . (Yii::$app->formatter->asPercent($total_goal_not_reached / $total_count)) . ')' : null,
        ],
        [
            'attribute' => 'script_worked',
            'value' => function (ByScriptsReport $model) {
                return Html::a($model->script_worked, ByScriptsReport::getByCallsReportLink($model->script_id, null, 1), ["target" => "blank"]);
            },
            'format' => 'raw',
            'footer' => $show_footer && $total_script_worked > 0 ? Html::a($total_script_worked, ByScriptsReport::getByCallsReportLink(null, null, 1), ["target" => "blank"]) . ' (' . (Yii::$app->formatter->asPercent($total_script_worked / $total_count)) . ')' : null,
        ],
        [
            'attribute' => 'script_broken',
            'value' => function (ByScriptsReport $model) {
                return Html::a($model->script_broken, ByScriptsReport::getByCallsReportLink($model->script_id, null, 0), ["target" => "blank"]);
            },
            'format' => 'raw',
            'footer' => $show_footer && $total_script_broken > 0 ? Html::a($total_script_broken, ByScriptsReport::getByCallsReportLink(null, null, 0), ["target" => "blank"]) . ' (' . (Yii::$app->formatter->asPercent($total_script_broken / $total_count)) . ')' : null,
        ]
    ],
]);