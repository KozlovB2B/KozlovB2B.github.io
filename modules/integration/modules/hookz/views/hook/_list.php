<?php
use yii\grid\GridView;
use app\modules\integration\modules\hookz\models\Hook;
use app\modules\core\components\widgets\GlyphIcon;
use app\modules\user\models\UserHeadManager;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\modules\integration\modules\hookz\components\HookEvent;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$head_manager = UserHeadManager::findHeadManagerByUser();

$query = Hook::find()->andWhere(['head_id' => $head_manager->id]);

$dataProvider = new ActiveDataProvider([
    'query' => $query,
    'sort' => [
        'defaultOrder' => [
            'id' => SORT_DESC,
        ]
    ]
]);

Pjax::begin(['id' => 'integration___hookz__hook_grid', 'enablePushState' => false, 'options' => ['url' => Url::to(['/integration/hookz/hook/list'])]]);


echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{pager}",
    'showHeader' => false,
    'columns' => [
        [
            'attribute' => 'event',
            'value' => function (Hook $model) {
                return HookEvent::getList()[$model->event];
            }
        ],
        [
            'attribute' => 'event',
            'format' => 'raw',
            'value' => function (Hook $model) {
                return  Html::tag('small', $model->get, ['class' => 'monospaced longword']);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => "{update} {delete}",
            'controller' => '/integration/hookz/hook',
            'buttons' => [
                'update' => function ($url) {
                    return Html::a(GlyphIcon::i('pencil'), $url, ['data-container' => '#integration___hookz___update_hook_modal_pjax', 'class' => 'pjax-modal', 'data-pjax' => 0]);
                },
                'delete' => function ($url) {
                    return Html::a(GlyphIcon::i('trash'), $url, ['class' => 'pjax-delete text-danger', "data-warning" => "Удалить WebHook?", 'data-pjax' => 0]);
                }
            ]
        ],
    ],
]);

Pjax::end();