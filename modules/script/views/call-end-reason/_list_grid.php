<?php
/**
 * @var app\modules\script\models\CallEndReason $model
 * @var \yii\data\ActiveDataProvider $data_provider
 */
use \yii\helpers\Html;
use \rmrevin\yii\fontawesome\FA;
use \yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $data_provider,
    'layout' => "{items}",
    'showHeader' => false,
    'emptyText' => '',
    'columns' => [
        'name',
        [
            'class' => 'yii\grid\CheckboxColumn',
            'header' => null,
            'checkboxOptions' => function (\app\modules\script\models\CallEndReason $model, $key) {
                return ['value' => $key, 'checked' => $model->comment_required, 'class' => 'script___call_end_reason__list_toggle_comment_required', 'label' => $model->getAttributeLabel("comment_required")];
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'controller' => '/script/call-end-reason',
            'buttons' => [
                'delete' => function ($url) {
                    return Html::a(FA::icon('trash'), $url, ["class" => "script___call_end_reason__list_delete_button text-danger", "title" => Yii::t("script", "Delete")]);
                },
            ]
        ]
    ],
]);