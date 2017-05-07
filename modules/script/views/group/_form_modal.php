<?php
/** @var string $form_id */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\script\models\ar\Group;
use app\modules\script\models\ar\GroupVariant;

$group = new Group();
$group_variant = new GroupVariant();


Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Edit group')),
    'id' => $form_id . '_modal',
    'size' => Modal::SIZE_DEFAULT,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success', 'id' => $form_id . '_submit']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>

    <div class="row">
        <div class="col-xs-6">
            <?php $form = ActiveForm::begin(['action' => '#', 'id' => $form_id, 'enableAjaxValidation' => false, 'enableClientValidation' => false]); ?>

            <div class="hide">
                <?= $form->field($group, 'id')->hiddenInput()->label(false) ?>
                <?= $form->field($group, 'top')->hiddenInput()->label(false) ?>
                <?= $form->field($group, 'left')->hiddenInput()->label(false) ?>
                <?= $form->field($group, 'variants_sort_index')->hiddenInput()->label(false) ?>
            </div>
            <?= $form->field($group, 'name')->textInput() ?>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-xs-6">
            <div class="btn btn-success create-group_variant-embed-button" id="script___group_variant___create_embed">Добавить ответ</div>
            <div id="script___group___form_group_variants_sort_index_warning" class="small text-info" style="display: none">
                Порядок будет сохранен при нажатии кнопки Ok
            </div>
            <ul class="list-group group_variants-sortable-list" id="script___group_variant___sortable_list">
            </ul>
            <?php

            $form = ActiveForm::begin([
                'action' => '#',
                'id' => $group_variant_form_embed_id,
                'options' => [
                    'style' => 'display:none'
                ],
                'enableAjaxValidation' => false,
                'enableClientValidation' => false
            ]);

            ?>

            <?= $form->field($group_variant, 'content')->textarea(['id' => 'group_variant-embed-content']) ?>

            <?= $form->field($group_variant, 'target_id')->dropDownList([], ["prompt" => "-- " . $group_variant->getAttributeLabel('target_id'), 'id' => 'group_variant-embed-target_id']) ?>

            <div class="control-group">
                <button type="submit" class="btn btn-success">Ok</button>
                <div class="btn btn-default" id="script___group_variant___form_embed_hide">Отмена</div>
            </div>

            <?php

            ActiveForm::end();

            ?>

        </div>
    </div>

<?php

Modal::end();
