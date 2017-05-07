<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\core\components\Division;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Author */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <?php if (!$model->getIsNewRecord()): ?>
                <?= Html::img($model->avatar, ['class' => 'img-responsive']) ?>
            <?php endif; ?>

            <?= $form->field($model, 'avatar_file')->fileInput() ?>

            <?= $form->field($model, 'division')->dropDownList(Division::active(), ['disabled' => !$model->getIsNewRecord()]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'about')->textarea(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>