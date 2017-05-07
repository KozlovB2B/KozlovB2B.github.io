<?php
use app\modules\core\widgets\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use app\modules\user\models\User;
use app\modules\user\models\profile\ProfileRelation;
use app\modules\user\components\UserIndexAssetBundle;
use yii\widgets\Pjax;
use rmrevin\yii\fontawesome\FA;

/**
 * @var yii\data\ActiveDataProvider $data_provider
 * @var \app\modules\user\models\UserSearch $search
 * @var yii\web\View $this
 */

$this->title = 'Список пользователей';
$this->params['breadcrumbs'][] = $this->title;

UserIndexAssetBundle::register($this);
$this->registerJs('window["user-index"] = new UserIndex()');

?>
    <div class="row">
        <div class="col-xs-10">
            <?php $form = ActiveForm::begin([
                'id' => 'user___user___index_search_form',
                'action' => '/user/user/index',
                'layout' => 'inline',
                'method' => 'get',
                'enableAjaxValidation' => false,
                'enableClientValidation' => false
            ]); ?>

            <?= $form->field($search, 'id')->textInput(['placeholder' => 'ID']) ?>

            <?= $form->field($search, 'username')->textInput(['placeholder' => 'логин']) ?>

            <?= $form->field($search, 'email')->textInput(['placeholder' => 'email']) ?>

            <?= $form->field($search, 'profile')->dropDownList(ProfileRelation::profileNames(), ['prompt' => '-- выберите профиль']) ?>

            <?= DatePicker::widget([
                'model' => $search,
                'attribute' => 'created_at',
                'dateFormat' => 'php:Y-m-d',
                'options' => [
                    'placeholder' => 'дата регистрации',
                    'class' => 'form-control'
                ]
            ]) ?>

            <?= $form->field($search, 'creator')->textInput(['placeholder' => 'создатель']) ?>

            <?= Html::submitButton('Поиск', ['class' => 'btn btn-success']) ?>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-xs-2">
            <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-primary pull-right', 'id' => 'user___user___create_button']) ?>
        </div>
    </div>


    <br/>
<?php Pjax::begin(['id' => 'user___user__index_grid', 'timeout' => false, 'enablePushState' => false]); ?>

<?= GridView::widget([
    'dataProvider' => $data_provider,
    'columns' => [
        'id',
        'username',
        'email:email',
        [
            'attribute' => 'confirmed_at',
            'value' => function (User $model) {
                if ($model->getIsConfirmed()) {
                    return Html::tag('span', 'Подтвержден', ['class' => 'label label-success']);
                } else {
                    return Html::tag('span', 'Не подтвержден', ['class' => 'label label-default']);
                }
            },
            'format' => 'raw',
        ],
        [
            'header' => 'Профиль',
            'value' => function (User $model) {
                $result = [];

                foreach ($model->profileRelations as $r) {
                    $result[] = $r->profile->getName();
                }

                return implode('<br/>', $result);
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return Yii::$app->getFormatter()->asDate($model->created_at);
            }
        ],
        [
            'attribute' => 'creator_id',
            'value' => function (User $model) {
                return $model->creator_id ? $model->creator->username : 'сам';
            }
        ],
        [
            'header' => 'Блокировка',
            'value' => function (User $model) {
                if ($model->getIsBlocked()) {
                    return Html::a('Разблокировать', ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success user___user___block',
                        'data-blocked' => 1
                    ]);
                } else {
                    return Html::a('Блокировать', ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-danger user___user___block',
                        'data-blocked' => 0
                    ]);
                }
            },
            'format' => 'raw',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {login-using-key}',
            'buttons' => [
                'login-using-key' => function ($url, User $model) {
                    return Html::a(FA::i(FA::_SIGN_IN), ['login-using-key', 'id' => $model->id, 'key' => $model->auth_key], [
                        'title' => 'Авторизоваться как пользователь',
                        'data-pjax' => 0
                    ]);
                }
            ]
        ],
    ],
]);

Pjax::end();