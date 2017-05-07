<?php
use yii\grid\GridView;
use yii\helpers\Html;
use \app\modules\script\models\Call;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\modules\script\components\ByCallsReportAssetBundle;
use app\modules\core\components\widgets\GlyphIcon;
/**
 * @var yii\data\ActiveDataProvider $data_provider
 * @var \app\modules\script\components\ByCallsReport $report
 */

$this->title = Yii::t("script", 'By calls report');
$this->params['breadcrumbs'][] = $this->title;

ByCallsReportAssetBundle::register($this);
$this->registerJs("window['by_calls_report'] = new ByCallsReport();");

echo Yii::$app->controller->renderPartial('_filter', ['report' => $report, 'url' => '/script/report/by-calls']);
?>
    <br/>
<?= GridView::widget([
    'dataProvider' => $data_provider,
    'columns' => [
        'id',
        [
//            'header' => Yii::t("script", 'Date'),
            'attribute' => 'started_at',
            'format' => ['date', 'php:d.m.Y H:i:s']
        ],
        [
            'attribute' => 'duration',
            'value' => function (Call $model) {
                return gmdate("i:s", $model->duration);
            },
            'format' => 'raw',
        ],

        [
            'header' => \Yii::t("script", "Script"),
            'attribute' => 'script.name'
        ],
        [
//            'header' => \Yii::t("script", "Who called"),
            'attribute' => 'user_id',
            'value' => function (Call $model) {
                if ($model->api_user) {
                    return $model->api_user;
                } else if ($model->operator) {
                    return $model->operator->getFullNameAndLogin();
                } else if ($model->user) {
                    return $model->user->username;
                }

                return null;
            },
        ],

        [
            'attribute' => 'is_goal_reached',
            'value' => function (Call $model) {

                $text = $model->is_goal_reached ? Yii::t('script', 'Yes') : Yii::t('script', 'No');
                $badge = $model->is_goal_reached ? 'success' : 'danger';

                return Html::tag('span', $text, ["class" => "label label-$badge"]);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'normal_ending',
            'value' => function (Call $model) {

                $text = $model->normal_ending ? Yii::t('script', 'Well finished') : Yii::t('script', 'Abnormal termination');
                $badge = $model->normal_ending ? 'success' : 'danger';

                return Html::tag('span', $text, ["class" => "label label-$badge"]);
            },
            'format' => 'raw',
        ],
        [
            'format' => "raw",
            'attribute' => 'nodes_passed'
        ],
        [
            'format' => "raw",
            'attribute' => 'end_node_content',
            'value' => function (Call $model) {


                if ($model->end_node_stage) {
                    $text = '#' . $model->end_node_id . ' (' . $model->getStageName($model->end_node_stage) . ')';
                } else {
                    $text = '#' . $model->end_node_id;
                }

                $focus_node_link = Html::a($text, Url::to(['/script/script/edit', 'id' => $model->script_id, 'focus_node' => $model->end_node_id]), ['target' => '_blank']);

                return Html::tag('small', $focus_node_link . ' ' . mb_substr(strip_tags($model->end_node_content), 0, 100, 'utf-8'));
            },
        ],
        [
            'format' => "raw",
            'attribute' => 'comment',
            'value' => function (Call $model) {
                return Html::tag('small', $model->comment);
            },
        ],
//        [
//            'attribute' => Yii::t('script', 'Listen'),
//            'value' => function (Call $model) {
//                return Html::tag('span', Yii::t('script', 'listen'), ["class" => "btn btn-xs", "disabled" => 1]);
//            },
//            'format' => 'raw',
//        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {edit-script} {listen} {perform_page}',
            'buttons' => [
                'edit-script' => function ($url, Call $model) {
                    return Html::a(GlyphIcon::i('pencil'), '/script/script/edit?id=' . $model->script_id, ['target' => '_blank', "title" => Yii::t("script", "Edit script")]);
                },
                'view' => function ($url, Call $model) {
                    return Html::a(GlyphIcon::i('eye-open'), '/script/call/view?id=' . $model->id, ['target' => '_blank', "title" => Yii::t("yii", "View")]);
                },
                'listen' => function ($url, Call $model) {
                    return Html::a(GlyphIcon::i('play'), '/script/call/listen?id=' . $model->id, ['class' => 'script___report___play', "title" => Yii::t("script", "Listen")]);
                },
                'perform_page' => function ($url, Call $model) {
                    return $model->perform_page ? Html::a(GlyphIcon::i('link'), $model->perform_page, ['target' => '_blank', "title" => Yii::t("script", "Customer card")]) : null;
                },
            ]
        ],
    ],
]);