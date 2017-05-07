<?php
use \yii\helpers\Html;

/**
 * @var app\modules\billing\models\Invoice $model
 */
?>
<table>
    <tr>
        <td>
            <img  src="https://scriptdesigner.ru/static/logo.png">
        </td>
        <td>
            <strong><?= $model->claimer->company_name ?></strong><br/>
            <span><?= $model->claimer->post_address ?></span><br/>
            <span>Телефон: <?= $model->claimer->contact_phone ?></span><br/>
            <span>ИНН: <?= $model->claimer->inn ?></span><br/>
        </td>
    </tr>
</table>
<br/>
<table border="1" cellpadding="4" style="border-collapse: collapse">
    <tr>
        <td>ИНН <?= $model->claimer->inn ?></td>
        <td>КПП</td>
        <td rowspan="2">Сч. №</td>
        <td rowspan="2"> <?= $model->claimer->pay_score ?></td>
    </tr>

    <tr>
        <td colspan="2">
            Получатель<br/>
            <?= $model->claimer->company_name ?>
        </td>
    </tr>

    <tr>
        <td colspan="2" rowspan="2">
            Банк получателя<br/>
            <?= $model->claimer->bank_name ?>
        </td>
        <td>БИК</td>
        <td><?= $model->claimer->bik ?></td>
    </tr>

    <tr>
        <td>Сч. №</td>
        <td><?= $model->claimer->corr_score ?></td>
    </tr>
</table>
<h3 style="text-align: center">Счёт на оплату № <?= $model->name ?> от <?= Yii::$app->getFormatter()->asDate($model->created_at) ?></h3>

<table cellpadding="4" style="vertical-align: top">
    <tr>
        <td>Плательщик:</td>
        <td>
            <?= $model->payer->company_name ?>, <?= $model->payer->post_address ?>, ИНН <?= $model->payer->inn ?>, КПП <?= $model->payer->kpp ?>, 
            р/с <?= $model->payer->pay_score ?>, банк  <?= $model->payer->bank_name ?>, кор.счет <?= $model->payer->corr_score ?>, БИК <?= $model->payer->bik ?>
        </td>
    </tr>
</table>


<table border="1" cellpadding="4" style="border-collapse: collapse">
    <thead>
    <tr>
        <th>№</th>
        <th>Наименование товара, работ, услуг</th>
        <th>Ед. изм.</th>
        <th>Кол-во</th>
        <th>Цена без НДС</th>
        <th>Сумма без НДС</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td><?= $model->pay_for ?></td>
        <td>-</td>
        <td>1</td>
        <td><?= Yii::$app->getFormatter()->asCurrency($model->amount, "RUR") ?></td>
        <td><?= Yii::$app->getFormatter()->asCurrency($model->amount, "RUR") ?></td>
    </tr>
    </tbody>
    <tfoot >
    <tr>
        <td colspan="5" style="border0; font-weight: bold; text-align: right">Итого без НДС</td>
        <td><?= Yii::$app->getFormatter()->asCurrency($model->amount, "RUR") ?></td>
    </tr>
    <tr>
        <td colspan="5" style="border0; font-weight: bold; text-align: right">Итого НДС</td>
        <td>---</td>
    </tr>
    <tr>
        <td colspan="5" style="border0; font-weight: bold; text-align: right">Всего к оплате:</td>
        <td><?= Yii::$app->getFormatter()->asCurrency($model->amount, "RUR") ?></td>
    </tr>
    </tfoot>
</table>

<span>Всего наименований 1, на сумму <?= Yii::$app->getFormatter()->asCurrency($model->amount, "RUR") ?>, без НДС</span><br/>
<strong><?= $model->num2str($model->amount) ?></strong>


<br/><br/>


<strong>Дополнительная информация:</strong><br>
<span>Не является плательщиком НДС в соответствии с положениями ст.346.12 и 346.13 главы 26.2 Налогового
Кодекса Российской Федерации</span><br/>
<span>Счет действителен в течение 3 дней</span>

<br/>

<table>
    <tr>
        <td><?= $model->claimer->company_name ?></td>
        <td style="border-bottom: 1px solid #000; text-align: center"><img src="https://scriptdesigner.ru/static/sign.jpg"></td>
        <td  style="border-bottom: 1px solid #000; text-align: center">/Ярополова Н. А./</td>
    </tr>
    <tr>
        <td></td>
        <td  style="text-align: center; font-size: 10px">
            подпись
        </td>
        <td style="text-align: center; font-size: 10px">
            расшифровка подписи
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2"><img  src="https://scriptdesigner.ru/static/stamp.jpg"></td>
    </tr>
</table>