<?php
use yii\widgets\DetailView;
use app\modules\script\components\ChatBubbleAssetBundle;
use yii\helpers\Html;

/**
 * @var app\modules\script\models\Call $model
 * @var $this yii\web\View
 */

ChatBubbleAssetBundle::register($this);

$this->title = Yii::t('script', 'View call #{0}', [$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('script', 'By calls report'), 'url' => '/script/report/by-calls'];
$this->params['breadcrumbs'][] = $this->title;

$who_call = $model->operator ? $model->operator->getFullNameAndLogin() : $model->user->username;
?>

<div class="row">
    <div class="col-lg-4 col-xs-12">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
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
    <div class="col-lg-8 col-xs-12">
        <?php if (0): ?>
            <h4>Скопируйте историю разговора отсюда, если это нужно.</h4>
            <textarea class="form-control" rows="6"><?php

                $start = 0;

                foreach ($model->getConversationHistory() as $h) {
                    $start += $h['t'];

                    if ($h['e']) {
                        echo 'К (' . date('i:s', $start) . '): ' . $h['e'];
                        echo "\n";
                    }

                    if ($h['n']) {
                        echo 'О (' . date('i:s', $start) . '): ' . $h['n'];
                        echo "\n";
                    }
                } ?></textarea>
        <?php endif; ?>

        <?php

        $start = 0;

        foreach ($model->getConversationHistory() as $h) {
            $start += $h['t'];

            if ($h['e']) {
                echo Html::tag('div', $h['e'] . Html::tag('div', date('i:s', $start), ['class' => 'bubble-info text-right']), ['class' => 'bubble bubble-alt']);
            }

            if ($h['n']) {
                echo Html::tag('div', $h['n'] . Html::tag('div', '#' . $h['id'] . ', ' . date('i:s', $start), ['class' => 'bubble-info']), ['class' => 'bubble']);
            }
        } ?>
    </div>
</div>