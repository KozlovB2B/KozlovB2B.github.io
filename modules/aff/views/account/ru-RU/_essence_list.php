<?php
/**
 * @var app\modules\aff\models\Account $account
 * @var $this yii\web\View
 */

use yii\helpers\Html;

?>
<h4 class="text-center">
    Как работает партнерская программа?
    <br/>
    <small>(существенные условия)</small>
</h4>

<ol>
    <li>Когда кто-то переходит по Вашей партнерской ссылке и регистрируется в системе, в базе данных отмечается, что пользователь этот пришел от Вас.</li>
    <li>Список привлеченных пользователей по Вашей партнерской ссылке вы можете посмотреть в Личном кабинете на странице <?php echo Html::a("привлечённые пользователи", '/aff/attracted-users') ?>.</li>
    <li>Вы зарабатываете <?php echo $account->getPercent() ?>% от каждого внесения средств на баланс привлеченным вами пользователем.</li>

    <li>
        Минимальная сумма выплаты партнёрской комиссии составляет 1000 руб. Для получения выплаты необходимо оставить заявку на выплаты на странице "выплаты по партнёрской программе", указав сумму и способ оплаты
        (WebMoney или Банковский перевод для юридических лиц). Вывод средств осуществляется в течение 5 рабочих дней.
    </li>
    <li>Запрещены авторефералы, когда в качестве партнёра вы привлекаете самих себя и получаете комиссию от своего заработка или трат.</li>
    <li>Договор может быть расторгнут, если Вы не привлекаете ни одного платного пользователя в течение 6 месяцев.</li>
</ol>