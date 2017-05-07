<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\aff\models\PromoLinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\aff\components\PromoLinkAssetBundle;

PromoLinkAssetBundle::register($this);

$this->title = Yii::t('aff', 'Promo links');
$this->params['breadcrumbs'][] = ['label' => Yii::t('aff', 'Affiliate program'), 'url' => '/aff'];
$this->params['breadcrumbs'][] = $this->title;
?>
    <p>
        <?= Html::a(Yii::t('aff', 'Create promo link'), ['create'], ['class' => 'btn btn-success', 'id' => 'aff___promo_link__create_button']) ?>
    </p>

<?php

Pjax::begin(['id' => 'aff___promo_link__index_grid', 'timeout' => false, 'enablePushState' => false]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
//        'id',
//        'created_at:date',
//        'user_id',
//        'promo_code',
//        'host',
//        'query_string',
        'url:url',
        'hits',
        [
            'attribute' => 'money',
            'format' => ['currency', Yii::$app->params['currency']]
        ],
        'utm_medium',
        'utm_source',
        'utm_campaign',
        'utm_content',
        'utm_term',

        ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
    ],
]);

Pjax::end();

$this->registerJs("window['promo-link'] = new PromoLink();");