<?php
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\site\models\Instruction $searchModel
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use \rmrevin\yii\fontawesome\FA;
use app\modules\site\components\AssetBundle;

AssetBundle::register($this);
$this->title = Yii::t('site', 'Instructions');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::button(Yii::t('site', 'Add instruction'), ['class' => 'btn btn-success', "id" => "site___instruction__create"]) ?>
    <br/>
    <br/>
<?php Pjax::begin(['id' => 'site___instruction__manage_grid', 'timeout' => false, 'enablePushState' => false]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        [
            "attribute" => "status_id",
            "value" => function (app\modules\site\models\Instruction $model) {
                return $model->getStatusName();
            },
            'filter' => $searchModel->getStatuses()
        ],
        [
            "attribute" => "video",
            "format" => "raw",
            "value" => function (app\modules\site\models\Instruction $model) {
                return '<iframe width="200" height="150" src="'.$model->video.'" frameborder="0"></iframe>';
            }
        ],
        'description',
        [
            'attribute' => 'created_at',
            'format' => 'date'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete} {update}',
            'controller' => '/site/instruction',
            'buttons' => [
                'update' => function ($url) {
                    return Html::a(FA::icon('pencil'), $url, ["class" => "site___instruction__update", "title" => Yii::t("site", "Update ")]);
                },
                'delete' => function ($url) {
                    return Html::a(FA::icon('trash'), $url, ["class" => "site___instruction__delete text-danger", "title" => Yii::t("site", "Delete")]);
                },
            ]
        ],
    ],
]); ?>

<?php Pjax::end() ?>
<?php $this->registerJs("window['instruction'] = new Instruction();"); ?>