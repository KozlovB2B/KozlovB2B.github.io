<?php
/* @var $this yii\web\View */
/* @var $scripts_data_provider yii\data\ActiveDataProvider */
/** @var \app\modules\billing\models\Account $billing */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\integration\components\DetectorAssetBundle;


use \app\modules\script\models\ar\Script;

use app\modules\script\components\AssetBundle;
use \rmrevin\yii\fontawesome\FA;

AssetBundle::register($this);
DetectorAssetBundle::register($this);



\Yii::$app->getModule("script");
$this->registerJs("new Script();");
$this->registerJs("new IntegrationDetector();");


$execution_allowed = Yii::$app->getUser()->can('script___call__perform');

?>
    <div class="row">
        <div class="col-xs-12">
            <?php
            if (!$execution_allowed)
                echo Html::tag('div', $billing->executionsLimitErrorMessage(), ['class' => 'alert alert-warning']);
            ?>
        </div>
    </div>
    <br/>
    <div id="integration___detector_content" class="alert alert-success" style="display: none;"></div>
<?php Pjax::begin(['id' => 'script___script__main_page_list_grid']); ?>
<?= GridView::widget([
    'dataProvider' => $scripts_data_provider,
    'layout' => "{items}\n{pager}",
    'showHeader' => false,
    'tableOptions' => ['class' => 'table table-striped'],
    'columns' => [
        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function (Script $model) {
                return '#' . $model->id . '&nbsp;' . $model->name;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => $execution_allowed ? '{perform}' : '',
            'buttons' => [
                'perform' => function ($url, Script $model) {
                    return Html::a(FA::icon(FA::_PHONE), "/api/v1/call/perform?script_id=" . $model->id, ["title" => Yii::t('script', 'Start up'), 'data-pjax' => 0]);
                }
            ]
        ],
    ],
]); ?>
<?php Pjax::end(); ?>