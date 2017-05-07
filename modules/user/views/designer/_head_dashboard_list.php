<?php

use app\modules\core\components\widgets\GlyphIcon;
use app\modules\user\models\profile\Designer;
use app\modules\user\models\Token;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\modules\user\models\profile\TeamMemberProfile;

/* @var $this yii\web\View */
/* @var $scripts_data_provider yii\data\ActiveDataProvider */

?>
<?php Pjax::begin(['id' => 'user___designer__head_dashboard_list_grid', 'timeout' => 10000, 'enablePushState' => 0, 'enableReplaceState' => 0, 'options' => ['url' => Url::to(['/user/designer/head-dashboard-list'])]]); ?>

<?php
$data_provider = Designer::headList();


if ($data_provider->getTotalCount()) : ?>
    <?= GridView::widget([
        'dataProvider' => $data_provider,
        'showHeader' => false,
        'layout' => "{items}\n{pager}",
        'columns' => [
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
                            Html::tag('i', '<br/>(отправьте ссылку проектировщику, если не придет письмо)', ['class' => 'small']);
                        } else {
                            return 'Не регистрировался';
                        }
                    } else if ($model->user->getIsBlocked()) {
                        return Html::tag('span', 'Заблокирован', ['class' => 'label label-danger']);
                    } else {
                        return Html::tag('span', 'Активен', ['class' => 'label label-success']);
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="text-right">{update} {block-unblock}</div>',
                'controller' => '/user/designer',
                'buttons' => [
                    'block-unblock' => function ($url, TeamMemberProfile $model, $key) {
                        if ($model->user) {
                            if ($model->user->getIsBlocked()) {
                                if (Yii::$app->getUser()->can('site___user_operator__create')) {
                                    return Html::a(GlyphIcon::i('user'), $url, ["title" => Yii::t("site", "Unblock designer"), "class" => "pjax-grid-func qtipped text-success", 'data-add-container' => "user___head__team_invite_buttons", 'data-my' => "top right", 'data-at' => "bottom left", 'data-pjax' => 0, "data-warning" => "Разблокировать проектировщика?"]);
                                }
                            } else {
                                return Html::a(GlyphIcon::i('remove'), $url, ["title" => Yii::t("site", "Block designer"), "class" => "pjax-grid-func qtipped text-danger", 'data-add-container' => "user___head__team_invite_buttons", 'data-my' => "top right", 'data-at' => "bottom left", 'data-pjax' => 0, "data-warning" => "Заблокировать проектировщика?"]);
                            }
                        }

                        return null;
                    },
                    'update' => function ($url, TeamMemberProfile $model, $key) {
                        return Html::a(GlyphIcon::i('pencil'), $url, ["class" => "pjax-modal qtipped", 'data-pjax' => 0, 'data-my' => "top right", 'data-at' => "bottom left", "data-container" => "#user___designer__update_modal_pjax", "title" => Yii::t("site", "Update designer's data")]);
                    }
                ]
            ]
        ]
    ]); ?>
<?php else: ?>
    <div class="well operators__banner">
        <div class="pull-left operators__picture">
            <?php echo GlyphIcon::i('education') ?>
        </div>

        <p class="operators__message">
            Проектировщики могут звонить, редактировать и публиковать скрипты, но не удалять.
            <br/>
            <br/>
            Так же они имеют доступ к отчетам.
        </p>
    </div>
<?php endif; ?>
<?php Pjax::end() ?>
