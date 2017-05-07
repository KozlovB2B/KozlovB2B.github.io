<?php
/**
 * @var app\modules\script\models\Script $model
 * @var  \yii\web\View $this
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('script', 'Import from file');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-xs-offset-3">

            <?php $form = ActiveForm::begin([
                'id' => 'script___script__import_form',
                'action' => '/script/script/import',
                'options' => ['enctype' => 'multipart/form-data'],
                'enableAjaxValidation' => false,
                'enableClientValidation' => false
            ]);
            ?>
            <?= $form->field($model, 'import_file')->fileInput(['accept' => '.scrd']) ?>
            <?php echo Html::submitButton(Yii::t('script', 'Import'), ['class' => 'btn btn-success']) ?>
            <?php
            ActiveForm::end(); ?>

        </div>
    </div>
</div>
