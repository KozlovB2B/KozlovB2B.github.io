<?php
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\profile\Operator */
/* @var $form ActiveForm */

?>
<?= $form->field($model, 'last_name')->textInput() ?>
<?= $form->field($model, 'first_name')->textInput() ?>