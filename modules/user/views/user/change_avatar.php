<?php
use yii\helpers\Html;
use app\modules\user\models\Avatar;

/* @var \app\modules\user\models\ChangeAvatarForm $model */
/* @var $this yii\web\View */

$this->title = 'Загрузка аватарки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
            <?= Html::img(Avatar::current()->getUrl(), [
                'id' => 'user___user__avatar_img',
                'class' => 'pointer',
                'data-toggle' => 'modal', 'data-target' => '#user___user__change_avatar_form_modal'
            ]); ?>

            <?= $this->render('_change_avatar_modal', ['model' => $model]); ?>
        </div>
    </div>
</div>
