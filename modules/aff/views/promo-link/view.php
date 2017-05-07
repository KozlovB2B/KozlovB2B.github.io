<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\aff\models\PromoLink */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('aff', 'Promo Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-link-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('aff', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('aff', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('aff', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            'user_id',
            'promo_code',
            'host',
            'query_string',
            'url:url',
            'utm_medium',
            'utm_source',
            'utm_campaign',
            'utm_content',
            'utm_term',
            'hits',
            'money',
        ],
    ]) ?>

</div>
