<?php
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\profile\Admin */
/* @var $form ActiveForm */
?>
<?= $form->field($model, 'first_name')->textInput() ?>
<?= $form->field($model, 'last_name')->textInput() ?>