<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\aff\models\PromoLinkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promo-link-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'promo_code') ?>

    <?= $form->field($model, 'host') ?>

    <?php // echo $form->field($model, 'query_string') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'utm_medium') ?>

    <?php // echo $form->field($model, 'utm_source') ?>

    <?php // echo $form->field($model, 'utm_campaign') ?>

    <?php // echo $form->field($model, 'utm_content') ?>

    <?php // echo $form->field($model, 'utm_term') ?>

    <?php // echo $form->field($model, 'hits') ?>

    <?php // echo $form->field($model, 'money') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('aff', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('aff', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
