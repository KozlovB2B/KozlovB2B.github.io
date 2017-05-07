<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\PjaxAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\profile\Head */
/* @var $form ActiveForm */
$model->accept_terms = 1;

PjaxAsset::register($this);
?>
<?= $form->field($model, 'last_name')->textInput() ?>
<?= $form->field($model, 'first_name')->textInput() ?>
<?= $form->field($model, 'middle_name')->textInput() ?>

<?= $form->field($model, 'skype')->textInput() ?>
