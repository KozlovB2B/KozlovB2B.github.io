<?php
/** @var app\modules\billing\models\Account $model */
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin([
    'id' => 'billing___account__update_form',
    'action' => '/billing/account/update?id=' . $model->id,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
Modal::begin([
    'header' => Html::tag("strong", 'Редактировать данные пользователя'),
    'id' => 'billing___account__update_modal',
    'size' => Modal::SIZE_DEFAULT,
    'footer' => Html::submitButton(Yii::t('site', 'Save'), ['class' => 'btn btn-success']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]); ?>
        <?= $form->field($model, 'blocked', [])->checkbox()
            ->hint(Html::tag("small", "Если активировать эту блокировку - пользователь сможет войти в аккаунт, но функционал его будет ограничен."))?>
        <?= $form->field($model, 'is_trial', [])->checkbox()
            ->hint(Html::tag("small", "Если включить триал, но оставить дату окончания триала в прошлом - то система автоматом снова завершит триал и применит ограничения бесплатного тарифа к аккаунту пользователя."))?>
        <?= $form->field($model, 'trial_till', [])->textInput(['type' => 'date'])
            ->hint(Html::tag("small", "Дата, когда завершится триал и система применит к аккаунту ограничения бесплатного тарифа.")) ?>
        <?= $form->field($model, 'min_balance', [])
            ->textInput()
            ->hint(Html::tag("small", "Минимальный баланс при достижении которого функционал главного пользователя блокируется, так же блокируются все его операторы.")) ?>


<?php
Modal::end();
ActiveForm::end();