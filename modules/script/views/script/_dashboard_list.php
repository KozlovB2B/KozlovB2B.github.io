<?php

use app\modules\billing\models\Account as BillingAccount;
use app\modules\core\components\widgets\GlyphIcon;
use app\modules\script\models\ar\Script;
use app\modules\user\models\profile\Head;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\modules\user\models\UserHeadManager;

/* @var $this yii\web\View */
/* @var $scripts_data_provider yii\data\ActiveDataProvider */

$hm = UserHeadManager::findHeadManagerByUser();
$execution_allowed = Yii::$app->getUser()->can('script___call__perform');
$export_allowed = Yii::$app->getUser()->can('script___script__export');


?>
<?php Pjax::begin(['id' => 'script___script__main_page_list_grid', 'timeout' => 10000, 'enablePushState' => 0, 'enableReplaceState' => 0, 'options' => ['data-url' => Url::to(['/script/script/dashboard-list'])]]); ?>

    <fieldset>
        <legend class="buttons text-right">

            <span class="pull-left">
                Скрипты
            </span>

            <div class="visible-xs">
                <?= Html::a(GlyphIcon::i('floppy-open') . ' ' . Yii::t('script', 'Import'), '#', ['data-toggle' => 'modal', 'data-target' => '#script___script__import_modal', 'class' => 'btn btn-primary btn-xs']) ?>

                <?php if (Yii::$app->getUser()->can('script___script__create')) : ?>
                    <?= Html::a(GlyphIcon::i('plus') . ' ' . Yii::t('script', 'Create'), "/script/script/create", ['target' => '_blank', 'class' => 'btn btn-success btn-xs', 'data-pjax' => 0]) ?>
                <?php endif; ?>
            </div>
            <div class="hidden-xs">
                <?= Html::a(GlyphIcon::i('floppy-open') . ' ' . Yii::t('script', 'Import from file'), '#', ['data-toggle' => 'modal', 'data-target' => '#script___script__import_modal', 'class' => 'btn btn-primary btn-sm']) ?>

                <?php if (Yii::$app->getUser()->can('script___script__create')) : ?>
                    <?= Html::a(GlyphIcon::i('plus') . ' ' . Yii::t('script', 'Create script'), "/script/script/create", ['target' => '_blank', 'class' => 'btn btn-success btn-sm', 'data-pjax' => 0]) ?>
                <?php endif; ?>
            </div>
        </legend>
    </fieldset>
<?php

if (!$execution_allowed) {
    $billing = BillingAccount::findOne($hm->id);
    echo Html::tag('div', $billing->executionsLimitErrorMessage(), ['class' => 'alert alert-warning']);
}

$scripts_data_provider->pagination->pageSize = 10;
$scripts_data_provider->pagination->route = Url::to(['/script/script/dashboard-list']);

$list_func = function (Script $model) {
    return "<strong>#" . $model->id . '</strong> ' . $model->name;
};

if($hm->create_builds_manually){

    $list_func = function (Script $model) {
        $name = "<strong>#" . $model->id . '</strong> ' . $model->name . ' ';

        $release_name = '';

        $delete_release = '';

        if ($model->latest_release) {
            $name .= Html::tag('span', 'Опубликован', ['class' => 'label label-success']);
            $release_name .= "<strong>Публикация: </strong>" . $model->release->version . ' ' . $model->release->name . ' (' . Yii::$app->getFormatter()->asDatetime($model->release->created_at, "short") . ')';
            $new_release_button_name = 'Новая публикация';

            $delete_release = Html::a('Снять с публикации', Url::to(['/script/release/delete', 'id' => $model->latest_release]), ["class" => "text-danger pjax-delete", "data-warning" => "Снять с публикации?", 'data-pjax' => 0]);

        } else {
            $name .= Html::tag('span', 'Не опубликован', ['class' => 'label label-default']);
            $new_release_button_name = 'Опубликовать';
        }

        $new_release_button = Html::a($new_release_button_name, Url::to(['/script/release/create', 'id' => $model->id]), ["class" => "strong text-success pjax-modal", "data-container" => "#script___release__create_modal_pjax", 'data-pjax' => 0]);

        return $name . ' ' . Html::tag('div', $release_name . " " . $new_release_button . "  " . $delete_release, ['class' => 'script-grid-release-info', 'title' => 'При публикации операторы получают доступ к скрипту и могут совершать по нему звонки.']);
    };
}

?>

<?php if ($scripts_data_provider->getTotalCount()) : ?>
    <?= GridView::widget([
        'dataProvider' => $scripts_data_provider,
        'layout' => "{items}\n{pager}",
        'showHeader' => false,
        'columns' => [
            [
                'format' => 'raw',
                'value' => $list_func
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => "<div class='text-right script-functions'>{edit} " . ($execution_allowed ? '{start_up}' : null) . ' ' . ($export_allowed ? '{export}' : '{export-restricted}') . ' {clone} {delete}</div>',
                'controller' => '/script/script',
                'buttons' => [
                    'edit' => function ($url, Script $model, $key) {
                        return Html::a(GlyphIcon::i('pencil'), $url, ["class" => "qtipped", "title" => Yii::t("script", "Update"), 'target' => '_blank', 'data-my' => "top right", 'data-at' => "bottom left", 'data-pjax' => 0]);
                    },
                    'start_up' => function ($url, Script $model, $key) {
                        return Html::a(GlyphIcon::i('earphone'), "#/call/" . $model->id, ["class" => "qtipped", 'data-my' => "top right", 'data-at' => "bottom left", "title" => Yii::t('script', 'Start up'), 'data-pjax' => 0]);
                    },
                    'export' => function ($url, Script $model, $key) {
                        return Html::a(GlyphIcon::i('floppy-save'), $url, ['target' => '_blank', 'download' => 1, 'class' => 'qtipped', 'data-my' => "top right", 'data-at' => "bottom left", 'data-pjax' => 0, "title" => Yii::t("script", "Export")]);
                    },
                    'clone' => function ($url, Script $model, $key) {
                        return Html::a(GlyphIcon::i('duplicate'), $url, ['class' => 'pjax-grid-func qtipped', 'data-my' => "top right", 'data-at' => "bottom left", 'data-pjax' => 0, "title" => Yii::t("script", "Duplicate")]);
                    },
                    'export-restricted' => function ($url) {
                        return Html::a(GlyphIcon::i('floppy-save'), $url, ['class' => 'qtipped script___script__export_restricted_modal_button', 'data-my' => "top right", 'data-at' => "bottom left", 'data-pjax' => 0, "title" => Yii::t("script", "Export")]);
                    },
                    'delete' => function ($url, Script $model) {
                        if(Yii::$app->getUser()->can('script___script__delete', ['script' => $model])){
                            return Html::a(GlyphIcon::i('trash'), $url, ['class' => 'pjax-delete text-danger qtipped', 'data-my' => "top right", 'data-at' => "bottom left", "data-warning" => "Удалить скрипт?", 'data-pjax' => 0, "title" => Yii::t("script", "Delete")]);
                        }

                        return null;
                    }
                ]
            ],
        ],
    ]); ?>
<?php elseif ( Yii::$app->getUser()->can('script___gift__accept') && Head::current()->info->gift_accepted === null): ?>
    <?php
    \app\modules\script\components\assets\GiftAsset::register($this);
    $this->registerJs('new Gift();')
    ?>
    <div class="well" id="script___gift__banner">
        <div class="pull-left script___gift__picture">
            <?php echo GlyphIcon::i('gift') ?>
        </div>
        <p class="script___gift__message"><strong>Мы приготовили для вас подарок:</strong>
            <br/>
            2 скрипта, которые вы можете настроить под себя.
            <br/>
            <?php echo Html::a('Принять', Url::to(['/script/gift/accept']), ['class' => 'script___gift__button btn btn-success btn-sm']) ?>
            <?php echo Html::a('Спасибо, не нужно', Url::to(['/script/gift/decline']), ['class' => 'script___gift__button btn btn-danger btn-xs']) ?>
        </p>
    </div>
<?php else: ?>
    <h2 class="muted">У вас нет скриптов</h2>
<?php endif; ?>
<?php Pjax::end(); ?>
<?php
// Модалы вставляются перед закрывающим тегом </body>
Yii::$app->controller->modals[] = $this->render("_import_modal");
