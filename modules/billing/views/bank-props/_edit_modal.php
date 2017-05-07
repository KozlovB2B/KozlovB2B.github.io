<?php
/** @var app\modules\billing\models\BankProps $props */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'action' => '/billing/bank-props/edit',
    'id' => "billing___bank_props__edit_from",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
Modal::begin([
    'header' => Html::tag("strong", Yii::t('billing', 'Edit props')),
    'id' => 'billing___bank_props__edit_modal',
    'size' => Modal::SIZE_LARGE,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>
<div class="row">
    <div class="col-lg-6">
        <?= $form->field($props, 'first_name')->textInput() ?>
        <?= $form->field($props, 'last_name')->textInput() ?>
        <?= $form->field($props, 'middle_name')->textInput() ?>
        <?= $form->field($props, 'contact_phone')->textInput() ?>
        <?= $form->field($props, 'company_name')->textInput() ?>
        <?= $form->field($props, 'inn')->textInput() ?>
        <?= $form->field($props, 'kpp')->textInput() ?>
        <?= $form->field($props, 'ogrn')->textInput() ?>
        <?= $form->field($props, 'bank_name')->textInput() ?>
        <?= $form->field($props, 'bik')->textInput() ?>
        <?= $form->field($props, 'corr_score')->textInput() ?>
        <?= $form->field($props, 'pay_score')->textInput() ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($props, 'boss_position')->textInput() ?>
        <?= $form->field($props, 'boss_last_name')->textInput() ?>
        <?= $form->field($props, 'boss_first_name')->textInput() ?>
        <?= $form->field($props, 'boss_middle_name')->textInput() ?>
        <?= $form->field($props, 'acting_on_the_basis')->textInput() ?>
        <?= $form->field($props, 'post_address')->textarea() ?>
        <?= $form->field($props, 'juristic_address')->textarea() ?>
        <?= $form->field($props, 'real_address')->textarea() ?>
    </div>
</div>


<?php
Modal::end();
ActiveForm::end();