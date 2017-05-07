<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\script\models\Script */

$this->title = Yii::t('script', 'Create script');
$this->params['breadcrumbs'][] = ['label' => Yii::t('script', 'Scripts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_editor_workspace', [
    'action' => "/script/script/create",
    'model' => $model,
    'focus_node' => null,
]) ?>

