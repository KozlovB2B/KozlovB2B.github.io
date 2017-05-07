<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\aff\models\Hit */

$this->title =  Yii::t('aff', 'Hit #{0}', [$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('aff', 'Affiliate program'), 'url' => '/aff'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('aff', 'Hits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
//            'user_id',
//            'promo_code',
//            'link_id',
            'query_string',
            'utm_medium',
            'utm_source',
            'utm_campaign',
            'utm_content',
            'utm_term',
            'ip',
            'user_agent',
            'browser_language',
            'device_type',
            'os',
            'browser',
            'ref',
            'has_registrations',
            'bills',
            'bills_paid',
            'total_earned',
        ],
    ]) ?>
