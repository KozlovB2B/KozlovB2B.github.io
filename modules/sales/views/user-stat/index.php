<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sales\models\UserStatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Активность клиентов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-stat-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => '/site/users',
                'template' => '{view}'
            ],
            'id',
            'user.username',
            [
                'header' => 'Имя',
                'value' => function ($model) {
                    $h = \app\modules\user\models\profile\Head::findOne($model->id);

                    if($h){
                        return $h->first_name;
                    }

                    return null;
                },
                'format' => 'html',
            ],
            'userHeadManager.phone',
            'user.email:email',
            [
                'attribute' => 'user.created_at',
                'value' => function ($model) {
                    return !empty($model->user->created_at) ? Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->user->created_at]) : null;
                }
            ],
            'current_balance',
            'scripts_created',
            'current_scripts_count',
            'current_nodes_count',
            'logins_today',
            'logins_yesterday',
            'logins_week',
            'executions_today',
            'executions_yesterday',
            'executions_week',
            [
                'attribute' => 'last_login',
                'value' => function ($model) {
                    return !empty($model->last_login) ? Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->last_login]) : null;
                }
            ]
        ]
    ]); ?>

</div>
