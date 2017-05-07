<?php
/**
 * @var View $this
 * @var ActiveDataProvider $data_provider
 * @var Operator $search_model
 */

use app\modules\user\models\profile\Operator;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\View;
use yii\widgets\Pjax;
use \app\modules\site\components\AssetBundle;
use \rmrevin\yii\fontawesome\FA;

AssetBundle::register($this);
?>
<?php Pjax::begin(['id' => 'site___user_operator__index_grid', 'timeout' => false, 'enablePushState' => false]); ?>
<?php
if (Yii::$app->getUser()->can('site___user_operator__create')) {
    echo Html::tag('p', Html::a(Yii::t('site', 'Add a user'), "/site/user-operator/create", ['class' => 'btn btn-success site___user_operator__create', 'disabled' => !Yii::$app->getUser()->can('site___user_operator__create')]));
}
?>
<?= GridView::widget([
    'dataProvider' => $data_provider,
//    'filterModel' => $search_model,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        [
            "attribute" => 'user.username',
            "filter" => false,
            "format" => 'html',
            "value" => function (Operator $model) {
                $user = $model->user;

                if (!$user) {
                    return null;
                }

                if ($user->getIsBlocked()) {
                    return Html::tag("small", $user->username . "&nbsp;" . Yii::t("site", "(blocked)"), ["class" => "text-danger"]);
                }

                return $user->username;
            }
        ],

        'user.email:email',
        'first_name',
        'last_name',
        [
            'attribute' => 'user.created_at',
            'format' => ['date', 'php:d.m.Y'],
            "filter" => false,
//            'filter' => DatePicker::widget([
//                'model' => $search_model->getUser(),
//                'attribute' => 'created_at',
//                'dateFormat' => 'php:Y-m-d',
//                'options' => [
//                    'class' => 'form-control'
//                ]
//            ]),
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{block-unblock} {update}', //{delete} todo продумать что будем делать с удалением оператора
            'controller' => '/site/user-operator',
            'buttons' => [
                'block-unblock' => function ($url, Operator $model, $key) {
                    if ($model->user) {
                        if ($model->user->getIsBlocked()) {
                            if (Yii::$app->getUser()->can('site___user_operator__create')) {
                                return Html::a(FA::icon('user', ["title" => Yii::t("site", "Unblock operator")]), $url, ["class" => "site___user_operator__block_unblock"]);
                            }
                        } else {
                            return Html::a(FA::icon('user-times', ["title" => Yii::t("site", "Block operator")]), $url, ["class" => "site___user_operator__block_unblock"]);
                        }
                    }
                },
                'update' => function ($url, Operator $model, $key) {
                    return Html::a(FA::icon('pencil'), $url, ["class" => "site___user_operator__update", "title" => Yii::t("site", "Update operator's data")]);
                },
                'delete' => function ($url, Operator $model, $key) {
                    return Html::a(FA::icon('trash'), $url, ["class" => "site___user_operator__delete text-danger", "title" => Yii::t("site", "Delete operator")]);
                },
            ]
        ],
//        [
//            'header' => Yii::t('user', 'Block status'),
//            'value' => function ($model) {
//                if ($model->isBlocked) {
//                    return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], [
//                        'class' => 'btn btn-xs btn-success btn-block',
//                        'data-method' => 'post',
//                        'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?')
//                    ]);
//                } else {
//                    return Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], [
//                        'class' => 'btn btn-xs btn-danger btn-block',
//                        'data-method' => 'post',
//                        'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?')
//                    ]);
//                }
//            },
//            'format' => 'raw',
//        ],
//        [
//            'class' => 'yii\grid\ActionColumn',
//            'template' => '{update} {delete}',
//        ],
    ],
]); ?>

<?php Pjax::end() ?>
<?php $this->registerJs("window['operator'] = new Operator();"); ?>