<?php
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\site\models\Instruction $searchModel
 */

use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\site\components\AssetBundle;

AssetBundle::register($this);
$this->title = Yii::t('site', 'Instructions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'showHeader' => false,
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    "attribute" => "video",
                    "format" => "raw",
                    "value" => function (app\modules\site\models\Instruction $model) {
                        return '<iframe width="300" height="220" src="' . $model->video . '" frameborder="0" allowfullscreen></iframe>';
                    }
                ],
                'description',
                [
                    'attribute' => 'created_at',
                    'format' => 'date'
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'controller' => '/site/instruction',
                    'buttons' => [
                        'view' => function ($url, app\modules\site\models\Instruction $model) {
                            return Html::a(Yii::t("site", "View"), '/instruction/' . $model->id, ["title" => Yii::t("site", "View")]);
                        }
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
