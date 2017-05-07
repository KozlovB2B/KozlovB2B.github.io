<?php

use app\modules\billing\models\Account as BillingAccount;
use app\modules\core\components\widgets\GlyphIcon;
use app\modules\script\models\ar\Script;
use app\modules\user\models\profile\Operator;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\modules\user\models\UserHeadManager;

/* @var $this yii\web\View */
/* @var $scripts_data_provider yii\data\ActiveDataProvider */
/** @var BillingAccount $billing */

$hm = UserHeadManager::findHeadManagerByUser();

$execution_allowed = Yii::$app->getUser()->can('script___call__perform');
$route = Url::to(['/script/script/operator-list']);

?>
<?php Pjax::begin(['id' => 'script___script__operator_list_grid', 'timeout' => 10000, 'enablePushState' => 0, 'enableReplaceState' => 0, 'options' => ['data-url' => $route]]); ?>

    <fieldset>
        <legend>
            Доступные вам скрипты
        </legend>
    </fieldset>
    <p>

    </p>
<?php

if (!$execution_allowed) {
    $billing = BillingAccount::findOne($hm->id);
    echo Html::tag('div', $billing->executionsLimitErrorMessage(), ['class' => 'alert alert-warning']);
}

if ($hm->create_builds_manually) {
    $query = Script::find()->byAccount(Operator::current()->head_id)->active()->published()->orderBy(['id' => SORT_DESC]);
    $list_func = function (Script $model) {
        $name = "<strong>#" . $model->id . '</strong> ' . $model->name . ' ';

        $release_name = "<strong>Публикация: </strong>" . $model->release->version . ' ' . $model->release->name . ' (' . Yii::$app->getFormatter()->asDatetime($model->release->created_at, "short") . ')';

        return $name . ' ' . Html::tag('div', $release_name . "  ", ['class' => 'script-grid-release-info']);
    };
} else {
    $query = Script::find()->byAccount(Operator::current()->head_id)->active()->orderBy(['id' => SORT_DESC]);

    $list_func = function (Script $model) {
        return "<strong>#" . $model->id . '</strong> ' . $model->name;
    };
}

?>

<?= GridView::widget([
    'dataProvider' => new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 10,
            'route' => $route,
        ]
    ]),
    'layout' => "{items}\n{pager}",
    'showHeader' => false,
    'columns' => [
        [
            'format' => 'raw',
            'value' => $list_func
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => "<div class='text-right script-functions'>" . ($execution_allowed ? '{start_up}' : null) . '</div>',
            'controller' => '/script/script',
            'buttons' => [
                'start_up' => function ($url, Script $model, $key) {
                    return Html::a(GlyphIcon::i('earphone'), "#/call/" . $model->id, ["class" => "qtipped", 'data-my' => "top right", 'data-at' => "bottom left", "title" => Yii::t('script', 'Start up'), 'data-pjax' => 0]);
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end(); ?>