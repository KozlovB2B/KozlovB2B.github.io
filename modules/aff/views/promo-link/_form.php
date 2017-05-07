<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\aff\models\PromoLink */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promo-link-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'promo_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'host')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'query_string')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_medium')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_campaign')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_term')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hits')->textInput() ?>

    <?= $form->field($model, 'money')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('aff', 'Create') : Yii::t('aff', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
