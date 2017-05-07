<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/** @var app\modules\user\models\UserHeadManager $model */

$form = ActiveForm::begin([
    'action' => '/site/users/change-password?id=' . $model->id,
    'id' => "site___users___change_password_form",
    'layout' => "horizontal",
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);

Modal::begin([
    'header' => Html::tag("strong", 'Установка пароля'.$model->user->username),
    'id' => 'site___users___change_password_modal',
    'size' => Modal::SIZE_DEFAULT,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>

<?= $form->field($model->user, 'password', [])->textInput() ?>
<?php
Modal::end();
ActiveForm::end();