<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */

$this->title = Yii::t('script', 'Calls');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="script-index">

    <?= GridView::widget([
        'dataProvider' => $data_provider,
        'columns' => [
            'id',
            [
                'header' => \Yii::t("script", "Script"),
                'attribute' => 'script.name'
            ],

            'script_version',
            [
                'attribute' => 'started_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],
            [
                'attribute' => 'ended_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],

            [
                'header' => \Yii::t("script", "Call end reason"),
                'attribute' => 'reason.name'
            ],
            [
                'format' => "raw",
                'attribute' => 'comment'
            ],

//            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller' => '/script/call'],
        ],
    ]); ?>

</div>
