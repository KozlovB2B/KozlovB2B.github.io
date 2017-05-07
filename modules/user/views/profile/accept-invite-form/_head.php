<?php
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\profile\Head */
/* @var $form ActiveForm */
?>
<?= $form->field($model, 'last_name')->textInput() ?>
<?= $form->field($model, 'first_name')->textInput() ?>
<?= $form->field($model, 'middle_name')->textInput() ?>
<?= $form->field($model, 'skype')->textInput() ?>
<?= $form->field($model, 'accept_terms')->checkbox()->label($model->getAttributeLabel('accept_terms')) ?>
