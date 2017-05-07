<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\profile\Operator */
/* @var $form  yii\bootstrap\ActiveForm */
/* @var $saved boolean */

Modal::begin([
    'header' => Html::tag('h4', 'Данные оператора'),
    'id' => 'user___operator__update_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::button('Сохранить', ['class' => 'btn btn-success core___functions__trigger_modal_form_submit']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

Pjax::begin(['id' => 'user___operator__update_form_container', 'enablePushState' => false]);

if ($saved) {
    $this->registerJs('$("#user___operator__update_modal").modal("hide")');
    $this->registerJs('reloadPjax("user___operator__head_dashboard_list_grid");');
    $this->registerJs('reloadPjax("user___head__team_invite_buttons");');
    $this->registerJs('message("success", "Данные обновлены!");');
}

?>
<?php $form = ActiveForm::begin(['id' => 'user___operator__update_form', 'action' => Url::to(['/user/operator/update', 'id' => $model->user_id]), 'options' => ['data-pjax' => true]]); ?>

<?php echo $form->field($model, 'first_name')->textInput(); ?>

<?php echo $form->field($model, 'last_name')->textInput(); ?>

<?= $form->field($model, 'new_password')->textInput()->hint(Yii::t("site", "Write new password and press &laquo;Save&raquo; button to change operator's current password."), ["class" => "help-hint small"]) ?>

<?php ActiveForm::end() ?>

<?php

Pjax::end();

Modal::end();