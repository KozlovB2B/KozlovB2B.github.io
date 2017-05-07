<?php
/* @var \app\modules\user\models\ChangePasswordForm $model */
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Смена пароль';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::button('Сменить пароль', ['class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#user___user__change_password_modal']); ?>
<?= $this->render('_change_password_modal', ['model' => $model]); ?>