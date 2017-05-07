<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\billing\models\Rate;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/** @var app\modules\billing\models\Account $account */
/** @var app\modules\billing\models\Rate $rate */
/** @var app\modules\user\models\UserHeadManager $user */

$form = ActiveForm::begin([
    'action' => '/billing/account/set-rate?user_id=' . $account->id,
    'id' => "billing___account__set_rate_form",
    'layout' => "horizontal",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);

Modal::begin([
    'header' => Html::tag("strong", Yii::t('billing', 'Set plan')),
    'id' => 'billing___account__set_rate_modal',
    'size' => Modal::SIZE_DEFAULT,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>

    <p class="text-center">Установка тарифа для пользователя <?= $user->user->username ?></p>

<?= $form->field($rate, 'id')->dropDownList(ArrayHelper::map(Rate::find()->active()->forCurrentDivision()->all(), 'id', 'name'), ["id" => "billing___account__set_rate_form_id", 'prompt' => '-- установить индивидуально']) ?>
<div id="billing___account__set_rate_form_fields">
    <?= $form->field($rate, 'name', [])->textInput() ?>
    <?= $form->field($rate, 'monthly_fee', [])->textInput() ?>
    <?= $form->field($rate, 'operators_threshold', [])->textInput() ?>
    <?= $form->field($rate, 'executions_per_day', [])->textInput() ?>
    <?= $form->field($rate, 'executions_per_month', [])->textInput() ?>
    <?= $form->field($rate, 'export_allowed', [])->checkbox() ?>
</div>

<?php if ($account->is_trial) {
    echo Html::tag('div', 'При установке тарифа триальный период на аккаунте закончится!', ['class' => 'alert alert-danger text-center']);
} ?>

<?php
Modal::end();
ActiveForm::end();