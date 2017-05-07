<?php
/** @var Operator $model */
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\user\models\profile\Operator;

$model->email = $model->user->email;

$form = ActiveForm::begin([
    'id' => 'site___user_operator__update_form',
    'action' => '/user/operator/update?id=' . $model->id,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
Modal::begin([
    'header' => Html::tag("strong", Yii::t('site', "Update operator's data")),
    'id' => 'site___user_operator__update_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Save'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
//    'toggleButton' => ['label' => Yii::t('site', 'Close')],
]); ?>
    <input style="display:none"><input type="password" style="display:none">

<?= $form->field($model, 'first_name')->textInput() ?>
<?= $form->field($model, 'last_name')->textInput() ?>
<?= $form->field($model, 'email')->textInput() ?>
<?= $form->field($model, 'new_password', [])->textInput()->hint(Html::tag("small", Yii::t("site", "Write new password and press &laquo;Save&raquo; button to change operator's current password."))) ?>

<?php
Modal::end();
ActiveForm::end();