<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\aff\models\HitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'promo_code') ?>

    <?= $form->field($model, 'link_id') ?>

    <?php // echo $form->field($model, 'query_string') ?>

    <?php // echo $form->field($model, 'utm_medium') ?>

    <?php // echo $form->field($model, 'utm_source') ?>

    <?php // echo $form->field($model, 'utm_campaign') ?>

    <?php // echo $form->field($model, 'utm_content') ?>

    <?php // echo $form->field($model, 'utm_term') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'user_agent') ?>

    <?php // echo $form->field($model, 'browser_language') ?>

    <?php // echo $form->field($model, 'device_type') ?>

    <?php // echo $form->field($model, 'os') ?>

    <?php // echo $form->field($model, 'browser') ?>

    <?php // echo $form->field($model, 'ref') ?>

    <?php // echo $form->field($model, 'has_registrations') ?>

    <?php // echo $form->field($model, 'bills') ?>

    <?php // echo $form->field($model, 'bills_paid') ?>

    <?php // echo $form->field($model, 'total_earned') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('aff', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('aff', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
