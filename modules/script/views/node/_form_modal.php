<?php
/** @var string $form_id */
/** @var string $variant_form_embed_id */
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\modules\script\models\Call;
use app\modules\script\models\ar\Node;
use app\modules\script\models\ar\Variant;
use app\modules\user\models\UserHeadManager;

$node = new Node();

$variant = new Variant();


Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Edit node #') . Html::tag("span", '', ['id' => 'script___node___update_form_modal_node_id_heading']) . ' ' . Html::tag('span', Yii::t('script', 'Previous nodes:') . ' ' . Html::tag("span", '', ['id' => 'script___node___update_form_modal_previous_nodes_heading']), ['id' => 'script___node___update_form_modal_previous_nodes_heading_wrapper'])),
    'id' => $form_id . '_modal',
    'size' => Modal::SIZE_LARGE,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success', 'id' => $form_id . '_submit']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>


    <div id="script___node___update_form_warning" class="hide">
        <?php echo Yii::t('script', 'Are you sure you want to switch to editing another node? Changes in the current node will be saved.'); ?>
    </div>
    <div id="script___node___update_form_hide_warning" class="hide">
        <?php echo Yii::t('script', 'Are you sure you want to close modal? Changes in the current node will be saved.'); ?>
    </div>


    <div class="row">
        <div class="col-xs-8">
            <?php $form = ActiveForm::begin(['action' => '/editor/node/save', 'id' => $form_id, 'enableAjaxValidation' => false, 'enableClientValidation' => false]); ?>

            <div class="hide">
                <?= $form->field($node, 'id')->hiddenInput()->label(false) ?>
                <?= $form->field($node, 'top')->hiddenInput()->label(false) ?>
                <?= $form->field($node, 'left')->hiddenInput()->label(false) ?>
                <?= $form->field($node, 'variants_sort_index')->hiddenInput()->label(false) ?>
            </div>

            <?= $form->field($node, 'is_goal')->checkbox() ?>
            <?= $form->field($node, 'normal_ending')->checkbox() ?>
            <?= $form->field($node, 'call_stage_id')->dropDownList(Call::getStages(), ["prompt" => "-- " . Yii::t('script', 'select conversation stage')]) ?>

            <?= $form->field($node, 'groups')->textInput(["placeholder" => Yii::t('script', 'Use variant groups')]) ?>

            <div id="<?php echo $form_id . '_content_toolbar' ?>" style="display: none;">
                <div class="btn-group btn-group-sm" role="group">
                    <a class="btn btn-default glyphicon glyphicon-bold glyphicon" data-wysihtml5-command="bold" title="Жирный"></a>
                    <a class="btn btn-default glyphicon glyphicon-italic" data-wysihtml5-command="italic" title="С наклоном"></a>
                    <a class="btn btn-default glyphicon glyphicon-text-width" data-wysihtml5-command="underline"
                       title="Подчеркнутый"></a>
                    <a class="btn btn-default glyphicon glyphicon-text-height" data-wysihtml5-command="fontSize"
                       data-wysihtml5-command-value="large"></a>
                    <a class="btn btn-default glyphicon glyphicon-align-left" data-wysihtml5-command="justifyLeft"></a>
                    <a class="btn btn-default glyphicon glyphicon-align-center" data-wysihtml5-command="justifyCenter"></a>
                    <a class="btn btn-default glyphicon glyphicon-align-right" data-wysihtml5-command="justifyRight"></a>
                    <a class="btn btn-default glyphicon glyphicon-list" data-wysihtml5-command="insertUnorderedList"></a>

                    <?php echo $this->render('/field/_node_form_list') ?>

                </div>
                &nbsp;
                <div class="btn-group btn-group-sm" role="group">
                    <?php

                    $colors = [
                        "black",
                        "red",
                        "blue",
                        "green",
                        "maroon",
                        "yellow"
                    ];

                    foreach ($colors as $color): ?>
                        <a class="btn btn-wysi-color-<?= $color ?>" data-wysihtml5-command="foreColor" data-wysihtml5-command-value="<?= $color ?>">&nbsp;</a>
                    <?php endforeach; ?>
                </div>
                <a data-wysihtml5-action="change_view" class="btn btn-default btn-sm glyphicon glyphicon-console pull-right"></a>
            </div>
            <?= $form->field($node, 'content')->textarea(['rows' => 8])->label(false) ?>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-xs-4">
            <div class="btn btn-success create-variant-embed-button" id="script___variant___create_embed">Добавить ответ</div>
            <div id="script___node___form_variants_sort_index_warning" class="small text-info" style="display: none">
                Порядок будет сохранен при нажатии кнопки Ok
            </div>
            <ul class="list-group variants-sortable-list" id="script___variant___sortable_list">
            </ul>
            <?php

            $form = ActiveForm::begin([
                'action' => '#',
                'id' => $variant_form_embed_id,
                'options' => [
                    'style' => 'display:none'
                ],
                'enableAjaxValidation' => false,
                'enableClientValidation' => false
            ]);

            ?>

            <?= $form->field($variant, 'content')->textarea(['id' => 'variant-embed-content']) ?>

            <?= $form->field($variant, 'target_id')->dropDownList([], ["prompt" => "-- " . $variant->getAttributeLabel('target_id'), 'id' => 'variant-embed-target_id']) ?>

            <div class="control-group">
                <button type="submit" class="btn btn-success">Ok</button>
                <div class="btn btn-default" id="script___variant___form_embed_hide">Отмена</div>
            </div>

            <?php

            ActiveForm::end();

            ?>
        </div>
    </div>
<?php
Modal::end();
