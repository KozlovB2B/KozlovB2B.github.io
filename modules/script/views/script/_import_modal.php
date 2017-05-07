<?php
/* @var $this yii\web\View */
/* @var $form ActiveForm */

use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\modules\script\components\assets\ImportAsset;
use app\modules\script\models\form\ImportForm;
use yii\helpers\Url;

$model = new ImportForm();

ImportAsset::register($this);

$this->registerJs("new ImportForm(" . json_encode([
        'url' => Url::to(['/script/script/import']),
        'modalId' => 'script___script__import_modal',
        'saveButton' => 'script___script__import_button',
        'id' => $model->formName(),
    ]) . ")");

Modal::begin([
    'header' => Html::tag('h4', 'Импортировать скрипт'),
    'id' => 'script___script__import_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::button('Начать загрузку', ['id' => 'script___script__import_button', 'class' => 'btn btn-success', 'disabled' => true])
]);

echo Html::beginForm(Url::to(['/script/script/import']), 'post', [
    'id' => $model->formName(),
    'enctype' => 'multipart/form-data',
    'class' => 'dropzone'
]);
echo Html::endForm();

Modal::end();