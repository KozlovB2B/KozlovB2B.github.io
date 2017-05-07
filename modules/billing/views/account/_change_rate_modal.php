<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\billing\models\Rate;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/** @var app\modules\billing\models\Account $account */

$form = ActiveForm::begin([
    'action' => '/billing/account/change-rate',
    'id' => "billing___account__change_rate_form",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);

Modal::begin([
    'header' => Html::tag("strong", Yii::t('billing', 'Change plan')),
    'id' => 'billing___account__change_rate_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>

<?= $form->field($account, 'rate_id')->dropDownList(ArrayHelper::map(Rate::find()->active()->forCurrentDivision()->all(), 'id', 'name'), ["id" => "billing___account__rate_id"])->label(false) ?>

<?php if($account->is_trial){
    echo Html::tag('div', Yii::t('billing', 'once you chane pricing plan - your FREE trail period will be canceled automatically'), ['class' => 'alert alert-danger text-center']);
} ?>

<?php Pjax::begin(['id' => 'billing___rate__change_restrictions', 'timeout' => false, 'enablePushState' => false]); ?>
<?= $this->render("@app/modules/billing/views/rate/_change_restrictions", ['rate' => $account->rate]); ?>
<?php Pjax::end(); ?>

<?php
Modal::end();
ActiveForm::end();