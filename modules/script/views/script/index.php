<?php
/* @var $this yii\web\View */
/* @var $scripts_data_provider yii\data\ActiveDataProvider */
/** @var BillingAccount $billing */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use \app\modules\script\models\ar\Script;

use app\modules\script\components\AssetBundle;
use \rmrevin\yii\fontawesome\FA;
use  \app\modules\billing\models\Account as BillingAccount;

AssetBundle::register($this);
\Yii::$app->getModule("script");
$this->registerJs("new Script();");



$billing = BillingAccount::findOne(Yii::$app->user->getId());

$export_allowed = false;
$execution_allowed = Yii::$app->getUser()->can('script___call__perform');

if ($billing) {
    $export_allowed = !!$billing->export_allowed;
}

?>
    <div class="row">
        <div class="col-lg-6">
            <?php if (Yii::$app->getUser()->can('script___script__create')) : ?>
                <?= Html::a(FA::icon('pencil') . Yii::t('script', 'Create script'), "/script/script/create", ['class' => 'btn btn-success']) ?>
            <?php endif; ?>

            <?= Html::a(FA::icon(FA::_FILE) . Yii::t('script', 'Import from file'), '/script/script/import', ['id' => 'script___script__import_button', 'class' => 'btn btn-primary']) ?>
            <?php if (Yii::$app->getUser()->can("script___call_end_reason__manage") && false) {
                echo Html::a(Yii::t('script', 'Call end reasons'), "/script/call-end-reason/list", ['id' => 'script___call_end_reason__list_modal_button', 'class' => 'btn btn-primary pull-right']);
            } ?>
        </div>
        <div class="col-lg-6">
            <?php

            if (!$execution_allowed) {
                echo Html::tag('div', $billing->executionsLimitErrorMessage(), ['class' => 'alert alert-warning']);
            }

            ?>
        </div>
    </div>
    <br/>
<?php Pjax::begin(['id' => 'script___script__main_page_list_grid']); ?>
<?= GridView::widget([
    'dataProvider' => $scripts_data_provider,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        [
            "attribute" => "status_id",
            "value" => function (\app\modules\script\models\ar\Script $model) {
                return $model->getStatusName();
            }],
        'name',
//        'import_id',
//        'import_version',
//        'original_id',
//        'original_version',

        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:d.m.Y']
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => ($execution_allowed ? '{start_up}' : null) . ' {update} ' . ($export_allowed ? '{export}' : '{export-restricted}') . ' {capture-script} {delete}',
            'controller' => '/script/script',
            'buttons' => [
                'start_up' => function ($url, Script $model, $key) {
                    return Html::a(FA::icon(FA::_PHONE), "/script/call/perform?id=" . $model->id, ["title" => Yii::t('script', 'Start up'), 'target' => '_blank', 'data-pjax' => 0]);
                },
                'update' => function ($url, Script $model, $key) {
                    return Html::a(FA::icon('pencil'), $url, ["title" => Yii::t("script", "Update"), 'target' => '_blank', 'data-pjax' => 0]);
                },
                'export' => function ($url, Script $model, $key) {
                    return Html::a(FA::icon(FA::_SAVE), $url, ['target' => '_blank', 'download' => 1, 'class' => 'no-pjax', 'data-pjax' => 0, "title" => Yii::t("script", "Export")]);
                },
                'capture-script' => function ($url) {
                    return Html::a(FA::icon(FA::_FILE_IMAGE_O), $url, ['target' => '_blank', 'class' => 'no-pjax', 'data-pjax' => 0, "title" => Yii::t("script", "Export to image")]);
                },
                'export-restricted' => function ($url) {
                    return Html::a(FA::icon(FA::_SAVE), $url, ['class' => 'script___script__export_restricted_modal_button', 'data-pjax' => 0, "title" => Yii::t("script", "Export")]);
                }
            ]
        ],
    ],
]); ?>
<?php Pjax::end(); ?>