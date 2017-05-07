<?php
/** @var string $form_id */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use app\modules\script\models\ar\Script;
use \yii\bootstrap\ActiveForm;

$script = new Script();

Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Script settings')),
    'id' => $form_id . '_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::submitButton(Yii::t('site', 'Ok'), ['class' => 'btn btn-success', 'id' => $form_id . '_submit']) . " " . Html::a(Yii::t('site', 'Cancel'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"]),
]); ?>
<?php $form = ActiveForm::begin(['action' => '/editor/script/save', 'id' => $form_id, 'enableAjaxValidation' => false, 'enableClientValidation' => false]); ?>
<?= $form->field($script, 'name')->textInput() ?>
<?= $form->field($script, 'start_node_id')->dropDownList([]) ?>
<?php
ActiveForm::end();
Modal::end();