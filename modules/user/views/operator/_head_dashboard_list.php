<?php

use app\modules\core\components\widgets\GlyphIcon;
use app\modules\user\models\profile\Operator;
use app\modules\user\models\Token;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\modules\user\models\profile\TeamMemberProfile;
use app\modules\user\components\EmployeeTrackerAsset;

/* @var $this yii\web\View */
/* @var $scripts_data_provider yii\data\ActiveDataProvider */


EmployeeTrackerAsset::register($this);

$this->registerJs("Yiij.app.setModule('employee-tracker', {
    'constructor' : EmployeeTracker
});");

?>
<style>
    /* Удалить это, когда ассеты будут у всех новые (после 2016) */
    .table tbody tr td.status-td{
        vertical-align: middle;
        text-align: center;
        font-size: 18px;
        width: 50px;
    }

    .user-status-text{
        margin-top: 3px;
        width: 120px;
    }

    table tr .user-grid-status {
        display: none;
    }

    tr[data-status="offline"] .user-status-offline {
        display: block;
    }

    tr[data-status="offline"] .user-status-offline {
        display: block;
    }

    tr[data-status="online"] .user-status-online {
        display: block;
    }

    tr[data-status="calling"] .user-status-calling {
        display: block;
    }
</style>

<?php Pjax::begin(['id' => 'user___operator__head_dashboard_list_grid', 'timeout' => 10000, 'enablePushState' => 0, 'enableReplaceState' => 0, 'options' => ['url' => Url::to(['/user/operator/head-dashboard-list'])]]); ?>

<?php

$data_provider = Operator::headList();


if ($data_provider->getTotalCount()) : ?>
    <?= GridView::widget([
        'dataProvider' => $data_provider,
        'showHeader' => false,
        'layout' => "{items}\n{pager}",
        'rowOptions' => ['data-status' => 'offline'],
        'columns' => [
//            [
//                "format" => 'html',
//                "contentOptions" => ['class' => 'status-td'],
//                "value" => function () {
//                    return GlyphIcon::i('user', ['class' => 'muted user-grid-status user-status-offline']) .
//                    GlyphIcon::i('user', ['class' => 'text-success user-grid-status user-status-online']) .
//                    GlyphIcon::i('earphone', ['class' => 'text-success user-grid-status user-status-calling']);
//                }
//            ],
            [
                "attribute" => 'user.username',
                "filter" => false,
                "format" => 'html',
                "value" => function (TeamMemberProfile $model) {
                    return $model->user->username . '<br/>' . $model->first_name . '  ' . $model->last_name;
                }
            ],
            [
                "attribute" => 'user.confirmed_at',
                "filter" => false,
                "format" => 'html',
                'value' => function (TeamMemberProfile $model) {
                    if (!$model->user->confirmed_at) {
                        /** @var Token $t */
                        $t = Token::find()->where('user_id=:user_id AND type=:type', [':user_id' => $model->id, ':type' => Token::TYPE_INVITE])->one();

                        if ($t) {
                            return Html::tag('span', 'Выслано приглашение', ['class' => 'label label-primary'])
                            . '<br/>' .
                            Html::a('Пригласительная ссылка', $t->getUrl(), ['class' => 'small']) .
                            Html::tag('i', '<br/>(отправьте ссылку оператору, если не придет письмо)', ['class' => 'small']);
                        } else {
                            return 'Не регистрировался';
                        }
                    } else if ($model->user->getIsBlocked()) {
                        return Html::tag('span', 'Заблокирован', ['class' => 'label label-danger']);
                    } else {
                        return Html::tag('span', 'Активен', ['class' => 'label label-success']);
//                        . "<br/>" .
//                        Html::tag('i', "Оффлайн", ['class' => 'small muted user-grid-status user-status-text user-status-offline']) .
//                        Html::tag('i', "Онлайн", ['class' => 'small text-success user-grid-status user-status-text user-status-online']) .
//                        Html::tag('i', "Выполняет звонок", ['class' => 'small text-success user-grid-status user-status-text user-status-calling']);
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="text-right">{update} {block-unblock}</div>',
                'controller' => '/user/operator',
                'buttons' => [
                    'block-unblock' => function ($url, TeamMemberProfile $model, $key) {
                        if ($model->user) {
                            if ($model->user->getIsBlocked()) {
                                if (Yii::$app->getUser()->can('site___user_operator__create')) {
                                    return Html::a(GlyphIcon::i('user'), $url, ["title" => Yii::t("site", "Unblock operator"), "class" => "pjax-grid-func qtipped text-success", 'data-add-container' => "user___head__team_invite_buttons", 'data-my' => "top right", 'data-at' => "bottom left", 'data-pjax' => 0, "data-warning" => "Разблокировать оператора?"]);
                                }
                            } else {
                                return Html::a(GlyphIcon::i('remove'), $url, ["title" => Yii::t("site", "Block operator"), "class" => "pjax-grid-func qtipped text-danger", 'data-add-container' => "user___head__team_invite_buttons", 'data-my' => "top right", 'data-at' => "bottom left", 'data-pjax' => 0, "data-warning" => "Заблокировать оператора?"]);
                            }
                        }

                        return null;
                    },
                    'update' => function ($url, TeamMemberProfile $model, $key) {
                        return Html::a(GlyphIcon::i('pencil'), $url, ["class" => "pjax-modal qtipped", 'data-pjax' => 0, 'data-my' => "top right", 'data-at' => "bottom left", "data-container" => "#user___operator__update_modal_pjax", "title" => Yii::t("site", "Update operator's data")]);
                    }
                ]
            ]
        ]
    ]); ?>
<?php else: ?>
    <div class="well operators__banner">
        <div class="pull-left operators__picture">
            <?php echo GlyphIcon::i('user') ?>
        </div>

        <p class="operators__message">
            Операторы видят публикации скриптов и могут выполнять звонки по ним.
            <br/>
            В зависимости от тарифа, вам может быть доступно разное количество сотрудников
        </p>
    </div>
<?php endif; ?>
<?php Pjax::end() ?>
