<?php
/** @var OperatorRegistrationForm $model */
/* @var app\modules\script\models\Script $script */

use app\modules\user\models\OperatorRegistrationForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;

Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Export is not allowed')),
    'id' => 'script___script__export_restricted_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::tag('div', Html::a(Yii::t('site', 'Yes'), '/billing', ['class' => 'btn btn-success']) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . Html::a(Yii::t('site', 'No'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),['class' => 'text-center'])
]); ?>
    <br/>
    <p class="lead text-center"><?= Yii::t('script', 'Only paid users can export scripts. Do you want to upgrade your account?') ?></p>
    <br/>
    <br/>
<?php
Modal::end();