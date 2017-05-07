<?php
use yii\widgets\DetailView;
use app\modules\script\components\ChatBubbleAssetBundle;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\script\components\ByCallsReportAssetBundle;
use app\modules\core\components\widgets\GlyphIcon;

/**
 * @var app\modules\script\models\Call $model
 * @var $this yii\web\View
 */

ChatBubbleAssetBundle::register($this);
ByCallsReportAssetBundle::register($this);
$this->registerJs("window['by_calls_report'] = new ByCallsReport();");


$this->title = Yii::t('script', 'View call #{0}', [$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('script', 'By calls report'), 'url' => '/script/report/by-calls'];
$this->params['breadcrumbs'][] = $this->title;

$who_call = $model->operator ? $model->operator->getFullNameAndLogin() : $model->user->username;
?>

<div class="row">
    <div class="col-xs-4">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
//                'account_id',
                'script.name',
                'script_version',
                [
                    'attribute' => 'started_at',
                    'format' => ['date', 'php:d.m.Y H:i:s']
                ],
                [
                    'attribute' => 'duration',
                    'value' => gmdate("i:s", $model->duration)
                ],
                [
                    'attribute' => 'record_url',
                    'format' => 'raw',
                    'visible' => !!$model->record_url,
                    'value' => $model->record_url ? Html::tag('audio', '', ['src' => $model->record_url, 'controls' => 1]) : null
                ],
                [
                    'attribute' => 'user_id',
                    'value' => $who_call,
                ],
                [
                    'attribute' => 'is_goal_reached',
                    'value' => Html::tag('span', $model->is_goal_reached ? Yii::t('script', 'Yes') : Yii::t('script', 'No'), ["class" => "label label-" . ($model->is_goal_reached ? 'success' : 'danger')]),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'normal_ending',
                    'value' => Html::tag('span', $model->normal_ending ? Yii::t('script', 'Well finished') : Yii::t('script', 'Abnormal termination'), ["class" => "label label-" . ($model->normal_ending ? 'success' : 'danger')]),
                    'format' => 'raw',
                ],
                'comment',
            ],
        ]) ?>
    </div>
    <div class="col-xs-8">
        <?php

        $start = 0;

        foreach ($model->getConversationHistory() as $h) {
            $start += $h['t'];

            if ($h['e']) {
                echo Html::tag('div', $h['e'] . Html::tag('div', date('i:s', $start), ['class' => 'bubble-info text-right']), ['class' => 'bubble bubble-alt']);
            }

            if ($h['n']) {

                $link = Html::a('#' . $h['id'], Url::to(['/script/script/edit', 'id' => $model->script_id, 'focus_node' => $h['id']]), ['target' => '_blank']);

                if ($model->record_url) {
                    $listen = Html::a(GlyphIcon::i('play'), '/script/call/listen?id=' . $model->id . '&start=' . $start, ['class' => 'script___report___play', "title" => Yii::t("script", "Listen")]) . ', ';
                } else {
                    $listen = null;
                }

                echo Html::tag('div', $h['n'] . Html::tag('div', $link . ', ' . $listen . date('i:s', $start), ['class' => 'bubble-info']), ['class' => 'bubble']);
            }
        } ?>
    </div>
</div>