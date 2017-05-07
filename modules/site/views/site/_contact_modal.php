<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

Modal::begin([
    'header' => Html::tag('h4', Yii::t('site', 'Contact us')),
    'id' => 'site___site__contact_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::a('Закрыть', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

Pjax::begin(['id' => 'site___site__contact_modal_container', 'enablePushState' => false]);

?>
    <p class="text-center">
        support(at)SalesScriptPROMPTER.com&nbsp;&nbsp;&nbsp; or &nbsp;&nbsp;&nbsp;&nbsp;<a href="https://www.facebook.com/groups/SalesScriptPROMPTER/"><img src="/static/FB-f-Logo__blue_50.png"></a>
    </p>
<?php

Pjax::end();

Modal::end();