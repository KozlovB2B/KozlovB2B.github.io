<?php
use yii\helpers\Html;
use app\modules\site\components\UsersBundle;
use yii\widgets\DetailView;
use app\modules\sales\models\UserStat;
UsersBundle::register($this);
/**
 * @var \app\modules\user\models\UserHeadManager $model
 */

$main_data = [
    'ID' => $model->id,
    'Логин' => $model->user->username,
    'Имя' => $model->user->profile->name,
    'Телефон' => $model->phone,
    'Email' => $model->user->email,
    'Время регистрации' => Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->user->created_at]),
    'Статус подтверждения' => $model->user->isConfirmed ? Html::tag('span', 'Подтвержден', ['class' => 'text-success']) : Html::tag('span', 'Не подтвержден', ['class' => 'text-danger']),
    'Статус блокировки' => $model->user->isBlocked ? Html::tag('span', 'Заблокирован', ['class' => 'text-danger']) : Html::tag('span', 'Активен', ['class' => 'text-success']),

];


$stat = [];

$finance = [

    'Текущий тариф' => $model->billing->rate_name,
    'Оплачено до' => Yii::$app->getFormatter()->asDate($model->billing->paid_till),
    'Баланс' => Yii::$app->getFormatter()->asCurrency($model->balance->balance, 'RUR'),
    'Триал до' => Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->billing->trial_till]),
    'Партнер' => $model->affiliate->affiliate ? $model->affiliate->affiliate->username : null,
    'Заработок партнера' => $model->affiliate->affiliate ? Yii::$app->getFormatter()->asCurrency($model->affiliate->total_affiliate_earned, 'RUR') : null,
];
?>

<div class="row">
    <div class="col-lg-4">
        <h4>Данные пользователя
            <span class="btn btn-primary btn-xs pull-right" data-id="<?php echo $model->id ?>" id="site___users___change_password">Сменить пароль</span></h4>
        </h4>
        <table class="table table-striped">
            <?php foreach ($main_data as $label => $value) {
                echo Html::tag('tr', Html::tag('td', Html::tag('strong', $label), ['style' => 'width:150px']) . Html::tag('td', $value));
            } ?>
        </table>
    </div>
    <div class="col-lg-4">
        <h4>Финансовая информация
            <span class="btn btn-primary btn-xs pull-right" data-id="<?php echo $model->id ?>" id="billing___account__update_button">Редактировать</span></h4>
        <span class="btn btn-success btn-xs pull-right" data-id="<?php echo $model->id ?>" id="billing___account__set_rate_button">Установить тариф</span></h4>
        <table class="table table-striped">
            <?php foreach ($finance as $label => $value) {
                echo Html::tag('tr', Html::tag('td', Html::tag('strong', $label), ['style' => 'width:150px']) . Html::tag('td', $value));
            } ?>
        </table>
    </div>
    <div class="col-lg-4">
        <h4>Сводная статистика</h4>
        <table class="table table-striped">
            <?php UserStat::aggregateByUser($model->id); ?>
            <?= DetailView::widget([
                'model' => $model->userStat,
                'attributes' => [
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

            <?php foreach ($stat as $label => $value) {
                echo Html::tag('tr', Html::tag('td', Html::tag('strong', $label), ['style' => 'width:150px']) . Html::tag('td', $value));
            } ?>
        </table>
    </div>
</div>
<?php
$this->registerJs("window['users'] = new Users();");
?>
