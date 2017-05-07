<?php
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use app\modules\user\models\UserHeadManager;
use rmrevin\yii\fontawesome\FA;


/**
 * @var yii\data\ActiveDataProvider $data_provider
 * @var \app\modules\user\models\UserHeadManagerSearch $search
 */


$this->title = 'Управление пользователями';
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin([
    'id' => 'site___user_head_manager___admin_search_form',
    'action' => '/site/users/admin',
    'layout' => 'inline',
    'method' => 'get',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>

<?= $form->field($search, 'id')->textInput(['placeholder' => 'ID']) ?>

<?= $form->field($search, 'email')->textInput(['placeholder' => 'email']) ?>

<?= $form->field($search, 'username')->textInput(['placeholder' => 'логин']) ?>

<?= $form->field($search, 'phone')->textInput(['placeholder' => 'телефон']) ?>

<?= $form->field($search, 'balance')->textInput(['placeholder' => 'баланс']) ?>

<?= $form->field($search, 'division')->dropDownList(UserHeadManager::divisions(), ['prompt' => 'подразделение']) ?>

<?php

echo DatePicker::widget([
    'model' => $search,
    'attribute' => 'created_at',
    'dateFormat' => 'php:Y-m-d',
    'options' => [
        'placeholder' => 'дата регистрации',
        'class' => 'form-control'
    ]
]);
?>



<?= Html::submitButton('Поиск', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
    <br/>
<?= GridView::widget([
    'dataProvider' => $data_provider,
    'columns' => [
        'id',
        'user.username',
        [
            'attribute' => 'user.profile.name',
            'value' => function ($model) {
                try{
                    return !empty($model->user->profile->name) ? $model->user->profile->name : null;
                }catch (Exception $e){
                    return null;
                }
            },
            'format' => 'html',
        ],
        'phone',
        'user.email:email',
        [
            'attribute' => 'user.registration_ip',

            'value' => function ($model) {
                if(!$model->user){
                    return null;
                }

                return $model->user->registration_ip == null
                    ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>'
                    : $model->user->registration_ip;
            },
            'format' => 'html',
        ],


        [
            'attribute' => 'user.created_at',
            'value' => function ($model) {
                return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->user->created_at]);
            }
        ],
        [
            'attribute' => 'billing.rate_name',
            'header' => 'Текущий тариф',
            'format' => 'raw',
        ],

        [
            'attribute' => 'balance',
            'header' => 'Баланс',
            'value' => function ($model) {
                return Yii::$app->getFormatter()->asCurrency($model->balance->balance, $model->balance->currency);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'division',
        ],
        [
            'header' => 'Подтверждение',
            'value' => function (UserHeadManager $model) {
                if ($model->user->isConfirmed) {
                    return '<div class="text-center"><span class="text-success">Активирован</span></div>';
                } else {
                    return Html::a('Активировать', ['confirm', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => 'Хотите активировать пользователя вручную?',
                    ]);
                }
            },
            'format' => 'raw'
        ],
        [
            'header' => 'Блокировка',
            'value' => function ($model) {
                if ($model->user->isBlocked) {
                    return Html::a('Разблокировать', ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => 'Вы дейстиветльно хотите разблокировать этого пользователя?'
                    ]);
                } else {
                    return Html::a('Блокировать', ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-danger btn-block',
                        'data-method' => 'post',
                        'data-confirm' => 'Вы дейстиветльно хотите заблокировать этого пользователя?'
                    ]);
                }
            },
            'format' => 'raw',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {login-using-key}',
            'buttons' => [
                'login-using-key' => function ($url, UserHeadManager $model) {
                    return Html::a(FA::i(FA::_SIGN_IN), ['login-using-key', 'id' => $model->user->id, 'key' => $model->user->auth_key], [
                        'title' => 'Авторизоваться как пользователь',
                        'data-pjax' => 0
                    ]);
                }
            ]
        ],
    ],
]);