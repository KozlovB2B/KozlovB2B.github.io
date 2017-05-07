<?php
/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var \app\modules\user\models\ChangeAvatarForm $model */

use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\components\ChangeAvatarAssetBundle;


ChangeAvatarAssetBundle::register($this);

Modal::begin([
    'header' => Html::tag('h4', 'Загрузить аватар'),
    'id' => 'user___user__change_avatar_form_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::button('Загрузить и сохранить', ['id' => 'user___user__upload_avatar_button', 'class' => 'btn btn-success', 'disabled' => true]) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);
echo Html::beginForm('/user/user/avatar', 'post', [
    'id' => $model->formName(),
    'enctype' => 'multipart/form-data',
    'class' => 'dropzone'
]);

echo Html::endForm();

Modal::end();