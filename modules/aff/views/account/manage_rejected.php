<?php
use yii\helpers\Html;
/**
 * @var app\modules\aff\models\Account $account
 */
$this->title = Yii::t('aff', 'Affiliate program');
$this->params['breadcrumbs'][] = $this->title;
\Yii::$app->getModule('billing');

?>



<div class="row">
    <div class="col-lg-4">
        <ul class="list-group">
            <?php if($account->affiliate_id): ?>
                <li class="list-group-item">
                    <strong>Вы зарегистрированы в системе по приглашению от:</strong> <?php echo $account->affiliate->username; ?>
                </li>
            <?php endif; ?>

            <li class="list-group-item">
                <strong>Всего заработано:</strong> <?php echo Yii::$app->getFormatter()->asCurrency($account->total_earned, 'RUR') ?>
            </li>

            <li class="list-group-item">
                <strong>Ваша ссылка для регистрации партнёров:</strong> <?php echo $account->getLink() ?>
            </li>

            <li class="list-group-item">
                <strong>Как работает партнерская программа?</strong><br/><br/>
                1. Когда кто-то переходит по Вашей партнерской ссылке и регистрируется в системе, в базе данных отмечается, что пользователь этот пришел от Вас.<br/><br/>
                2. Вы зарабатываете <?php echo $account->getPercent() ?>% от каждого внесения средств на баланс привлеченным вами пользователем.<br/><br/>
                3. Минимальная сумма выплаты партнёрской комиссии составляет 1000 руб. Для получения выплаты необходимо оставить заявку,
                указав сумму и способ оплаты (WebMoney или Банковский перевод для юридических лиц). Вывод средств осуществляется в течение 5 рабочих дней.<br/><br/>
                4. Запрещены авторефералы, когда в качестве партнёра вы привлекаете самих себя и получаете комиссию от своего заработка или трат.<br/><br/>
            </li>
        </ul>
    </div>
    <div class="col-lg-8">
        <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#aff___account__attracted_users_index_wrapper" id="aff___account__attracted_users_index_wrapper-tab" role="tab" data-toggle="tab" aria-controls="aff___account__attracted_users_index_wrapper" aria-expanded="true">Привлеченные пользователи</a>
                </li>
                <li role="presentation" class="">
                    <a href="#aff___account__accruals_index_wrapper" role="tab" id="aff___account__accruals_index_wrapper-tab" data-toggle="tab" aria-controls="aff___account__accruals_index_wrapper" aria-expanded="false">Начисления</a>
                </li>
                <li role="presentation" class="">
                    <a href="#aff___payouts__user_list_wrapper" role="tab" id="aff___payouts__user_list_wrapper-tab" data-toggle="tab" aria-controls="aff___payouts__user_list_wrapper" aria-expanded="false">Заявки на вывод</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="aff___account__attracted_users_index_wrapper" aria-labelledby="home-tab">
                    <br/>
                    <?= $this->render('@app/modules/aff/views/account/_attracted_users') ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="aff___account__accruals_index_wrapper">
                    <br/>
                    <?= $this->render('@app/modules/aff/views/account/_accruals_list') ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="aff___payouts__user_list_wrapper">
                    <br/>
                </div>
            </div>
        </div>
    </div>
</div>
