<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\UserStat */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Stats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-stat-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'current_balance',
            'comment',
            'scripts_created',
            'current_scripts_count',
            'current_nodes_count',
            'logins_today',
            'logins_yesterday',
            'logins_week',
            'executions_today',
            'executions_yesterday',
            'executions_week',
            'last_login',
        ],
    ]) ?>

</div>
