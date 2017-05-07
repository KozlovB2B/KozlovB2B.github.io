<?php
/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var \app\modules\user\models\profile\Profile $model */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>
<?php $form = ActiveForm::begin([
    'id' => 'user___profile__update_form',
    'action' => '/user/profile/update-by-admin?id=' . $model->user_id,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>
    <div class="form-group">
        <label class="control-label">Профиль</label>
        <?= Html::dropDownList('profile', $model->getType(), ArrayHelper::map($model->user->profileRelations, 'profile_class', 'name'), ['data-id' => $model->user_id, 'class' => 'form-control', 'id' => 'user___profile__update_form-profile']) ?>
    </div>
<?= Html::tag('div', $this->render('_' . $model->getCode(), ['model' => $model, 'form' => $form]), ['id' => 'user___profile__update_form-profile_fields']) ?>

    <div class="form-group">
        <?= Html::submitButton('Обновить данные профиля', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>