<?php
/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var \app\modules\user\models\profile\Profile $model */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\user\components\UserUpdateProfileAssetBundle;

UserUpdateProfileAssetBundle::register($this);
$this->registerJs('new UserUpdateProfile();');

?>
<?php $form = ActiveForm::begin([
    'id' => 'user___profile__user_update_form',
    'action' => '/profile',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false
]); ?>
<?= $this->render('_' . $model->getCode(), ['model' => $model, 'form' => $form]); ?>
    <div class="form-group">
        <?= Html::submitButton('Обновить данные профиля', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>