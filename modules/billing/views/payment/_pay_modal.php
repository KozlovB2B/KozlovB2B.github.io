<?php
/** @var Payment $model */
/** @var yii\web\View $this */
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\modules\billing\models\Payment;

$model = \Yii::createObject(Payment::className());

$model->component = 'cronopay';

$form = ActiveForm::begin([
    'action' => '/billing/payment/pay',
    'id' => "billing___payment__pay_form",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);

Modal::begin([
    'header' => Html::tag("strong", Yii::t('billing', 'Buy credits')),
    'id' => 'billing___payment__pay_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>
<?= $form->field($model, 'component')->dropDownList(\Yii::$app->controller->module->payment_services) ?>
<?= $form->field($model, 'sum')->textInput() ?>

<?php
Modal::end();
ActiveForm::end();