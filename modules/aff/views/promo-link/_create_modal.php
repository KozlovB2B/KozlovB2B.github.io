<?php
/** @var PromoLink $model */
/** @var ActiveForm $form */

use app\modules\aff\models\PromoLink;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'aff___promo_link__create_form',
    'action' => '/aff/promo-link/create',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);

Modal::begin([
    'header' => Html::tag("strong", Yii::t('aff', "Create promo link")),
    'id' => 'aff___promo_link__create_modal',
    'size' => Modal::SIZE_LARGE,
    'footer' => Html::submitButton(Yii::t('site', 'Save'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>
    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'utm_medium')->dropDownList($model->getUtmMediumVariants(), [
                'prompt' => '',
                'id' => 'aff___promo_link__create_form_utm_medium',
            ]); ?>

            <?= $form->field($model, 'utm_medium_other')->textInput([
                'id' => 'aff___promo_link__create_form_utm_medium_other',
                'placeholder' => 'Введите источник трафика',
                'style' => 'display:none'
            ])->label(false); ?>

            <?= $form->field($model, 'utm_source')->textInput(['class' => 'form-control aff___promo_link__create_form_utm', 'data-utm' => 'utm_source']); ?>
            <?= $form->field($model, 'utm_campaign')->textInput(['class' => 'form-control aff___promo_link__create_form_utm', 'data-utm' => 'utm_campaign']); ?>
            <?= $form->field($model, 'utm_content')->textInput(['class' => 'form-control aff___promo_link__create_form_utm', 'data-utm' => 'utm_content']); ?>
            <?= $form->field($model, 'utm_term')->textInput(['class' => 'form-control aff___promo_link__create_form_utm', 'data-utm' => 'utm_term']); ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'url')->textarea(['id' => 'aff___promo_link__create_form_url', 'rows' => 10]); ?>
        </div>
    </div>

<?= $form->field($model, 'promo_code')->hiddenInput(['id' => 'aff___promo_link__create_form_promo_code'])->label(false); ?>

<?= $form->field($model, 'host')->hiddenInput(['id' => 'aff___promo_link__create_form_host'])->label(false); ?>
<?= $form->field($model, 'query_string')->hiddenInput(['id' => 'aff___promo_link__create_form_query_string'])->label(false); ?>

<?php
Modal::end();
ActiveForm::end();