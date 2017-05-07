<?php
use yii\widgets\ListView;
use app\modules\integration\models\Integration;
use yii\data\ArrayDataProvider;
use app\modules\integration\components\AssetBundle;

$this->title = Yii::t("integration", 'Integrations');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var yii\web\View $this
 */

AssetBundle::register($this);
?>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
        <div class="integration___items text-center">
            <?= ListView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => Integration::getList(),
//                    'sort' => [
//                        'attributes' => ['id', 'username', 'email'],
//                    ],
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'summary' => false,
                'emptyText' => false,
                'itemView' => function (Integration $integration) {
                    return $this->render('_list_item', ['integration' => $integration]);
                },
            ]); ?>
        </div>
    </div>
</div>