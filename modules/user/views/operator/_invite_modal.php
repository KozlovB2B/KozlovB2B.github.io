<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\form\InviteOperatorForm */
/* @var $form  yii\bootstrap\ActiveForm */

Modal::begin([
    'header' => Html::tag('h4', 'Пригласить оператора'),
    'id' => 'user___operator__invite_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::button('Пригласить', ['class' => 'btn btn-success core___functions__trigger_modal_form_submit']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

Pjax::begin(['id' => 'user___operator__invite_form_container', 'enablePushState' => false]);

if (!$model->operator->getIsNewRecord()) {
    $this->registerJs('$("#user___operator__invite_modal").modal("hide")');
    $this->registerJs('reloadPjax("user___operator__head_dashboard_list_grid");');
    $this->registerJs('reloadPjax("user___head__team_invite_buttons");');
    $this->registerJs('message("success", "Приглашение выслано!");');
}

?>
<?php $form = ActiveForm::begin(['id' => 'user___operator__invite_form', 'action' => Url::to(['/user/operator/invite']), 'options' => ['data-pjax' => true]]); ?>

<?= $form->field($model, 'email')->textInput()->hint('На указанный email будет выслано письмо со ссылкой для завершения регистрации.', ['class' => 'help-hint small']); ?>

<?php echo $form->field($model->operator, 'first_name')->textInput(); ?>

<?php echo $form->field($model->operator, 'last_name')->textInput(); ?>

    <div class="small help-hint">Имя и Фамилию заполнять не обязательно. Оператор сам сможет их заполнить при завершении регистрации</div>

<?php ActiveForm::end() ?>

<?php

Pjax::end();

Modal::end();