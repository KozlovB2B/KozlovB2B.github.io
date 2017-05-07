<?php

/* @var $this yii\web\View */
/* @var $data_provider  yii\data\ActiveDataProvider */
/* @var $script_id  integer */


use app\modules\core\components\widgets\GlyphIcon;
use app\modules\script\models\ar\ScriptImage;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

Yii::$app->getFormatter()->sizeFormatBase = 1000;


Modal::begin([
    'header' => Html::tag('strong', Yii::t('script', 'Export to image')),
    'id' => 'script___script_image__list_modal',
    'size' => Modal::SIZE_DEFAULT,
    'footer' => null
]);

Pjax::begin(['id' => 'script___script_image__list_form_container', 'enablePushState' => false, 'enableReplaceState' => false, 'options' => ['url' => Url::to(['/script/script-image/list-modal', 'script_id' => $script_id])]]);

echo Html::a(GlyphIcon::i('plus') . ' ' . Yii::t('script', 'Export to image'), '#', ['id' => 'script___script_image__create', 'class' => 'btn btn-success btn-sm', 'data-pjax' => 0])

?>
    <br/>
    <div id="script___script_image__pending" class="text-center" style="display: none;">
        Происходит эскпорт скрипта в форматы SVG и PNG...
    </div>
    <br/>


<?= GridView::widget([
    'dataProvider' => $data_provider,
    'showOnEmpty' => false,
    'layout' => "{items}",
    'showHeader' => false,
    'columns' => [
        [
            'format' => 'raw',
            'value' => function (ScriptImage $model) {
                return Html::tag('div', GlyphIcon::i('picture'), ['class' => 'text-center']);
            }
        ],
        [
            'format' => 'raw',
            'value' => function (ScriptImage $model) {
                return Yii::$app->getFormatter()->asDatetime($model->created_at);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => "<div class='text-center'>{download-svg}</div>",
            'controller' => '/script/script-image',
            'buttons' => [
                'download-svg' => function ($url, ScriptImage $model, $key) {
                    $link = Html::a(GlyphIcon::i('save') . ' SVG', $model->getDownloadUrl('svg'), ['target' => '_blank', 'data-pjax' => 0]);
                    return $link . ' ' . Html::tag('i', Yii::$app->getFormatter()->asShortSize($model->svg_size, 1), ['class' => 'small']);
                },
            ]
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => "<div class='text-center'>{download-png}</div>",
            'controller' => '/script/script-image',
            'buttons' => [
                'download-png' => function ($url, ScriptImage $model, $key) {
                    $link = Html::a(GlyphIcon::i('save') . ' PNG', $model->getDownloadUrl('png'), ['target' => '_blank', 'data-pjax' => 0]);
                    return $link . ' ' . Html::tag('i', Yii::$app->getFormatter()->asShortSize($model->png_size, 1), ['class' => 'small']);
                }
            ]
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => "<div class='text-right'>{delete}</div>",
            'controller' => '/script/script-image',
            'buttons' => [
                'delete' => function ($url, ScriptImage $model, $key) {
                    return Html::a(GlyphIcon::i('remove'), $url, ['target' => '_blank', 'download' => 1, 'class' => 'qtipped text-danger pjax-delete', 'data-my' => "top right", 'data-at' => "bottom left", 'data-pjax' => 0, "title" => Yii::t("script", "Delete")]);
                }
            ]
        ]
    ]
]); ?>
<?php

Pjax::end();

Modal::end();