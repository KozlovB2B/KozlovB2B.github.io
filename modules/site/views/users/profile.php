<?php
use yii\widgets\DetailView;
use app\modules\script\components\ChatBubbleAssetBundle;
use yii\helpers\Html;

/**
 * @var app\modules\user\models\UserHeadManager $model
 * @var app\modules\script\models\ApiToken $token
 */

$this->title = Yii::t('site', 'Settings');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
setEvent("change", "#user_head__create_builds_manually", function () {
    ajax("/site/users/create-builds-manually?value=" + ($(this).prop("checked") ? 1 : 0));
    return true;
});');

$this->registerJs('
setEvent("change", "#user_head__enable_hits_report", function () {
    ajax("/site/users/enable-hits-report?value=" + ($(this).prop("checked") ? 1 : 0));
    return true;
});');

?>


<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <div class="checkbox">
            <label>
                <?php echo Html::checkbox('enable', !!(int)$model->create_builds_manually, ['id' => 'user_head__create_builds_manually']); ?>
                Публиковать скрипты вручную
            </label>
        </div>

        <div class="small help-hint">
            По-умолчанию операторам доступны все скрипты, а звонки совершаются по последним данным скрипта.
            <br/>
            Если вы вы хотите, чтобы операторы имели доступ только к опубликованным скриптам или работали по определенной версии скрипта,
            пока вы меняете или эксперементируете со скриптом - включите ручную публикацию.
        </div>
        <div class="checkbox">
            <label>
                <?php echo Html::checkbox('enable', !!(int)$model->hits_report, ['id' => 'user_head__enable_hits_report']); ?>
                Активировать отчет о популярности ответов
            </label>
        </div>

        <div class="small help-hint">
           Отчет о популярности ответов поможет вам узнать какие ответы узлов были наиболее популярны
        </div>
        <br/>
        <br/>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'user.username',
                'user.created_at:date',
//                'script.name',
//                'script_version',
                [
                    'label' => Yii::t('site', 'API key'),
                    'value' => $token->token
                ],
//                [
//                    'attribute' => 'duration',
//                    'value' => gmdate("i:s", $model->duration)
//                ],
//                [
//                    'attribute' => 'user_id',
//                    'value' => $who_call,
//                ],
//                [
//                    'attribute' => 'is_goal_reached',
//                    'value' => Html::tag('span', $model->is_goal_reached ? Yii::t('script', 'Yes') : Yii::t('script', 'No'), ["class" => "label label-" . ($model->is_goal_reached ? 'success' : 'danger')]),
//                    'format' => 'raw',
//                ],
//                [
//                    'attribute' => 'normal_ending',
//                    'value' => Html::tag('span', $model->normal_ending ? Yii::t('script', 'Well finished') : Yii::t('script', 'Abnormal termination'), ["class" => "label label-" . ($model->normal_ending ? 'success' : 'danger')]),
//                    'format' => 'raw',
//                ],
//                'comment',
            ],
        ]) ?>
    </div>
</div>
