<?php
/**
 * @var app\modules\script\models\CallEndReason $model
 * @var \yii\data\ActiveDataProvider $data_provider
 * @var  \yii\web\View $this
 */
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use \yii\grid\GridView;

Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Call end reasons')),
    'id' => 'script___call_end_reason__list_modal',
    'size' => Modal::SIZE_DEFAULT,
    'footer' => Html::a(Yii::t('site', 'Close'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

$form = ActiveForm::begin([
    'layout' => 'inline',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
    ],
    'id' => 'script___call_end_reason__create_form',
    'action' => '/script/call-end-reason/create',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
?>
    <input style="display:none"><input type="password" style="display:none">
    <p>


        <?= $form->field($model, 'name')->textInput(["class" => "inline-middle-field form-control", "placeholder" => $model->getAttributeLabel("name")]) ?>
        <?= $form->field($model, 'comment_required', [])->checkbox(["title" => Yii::t("script", "If activated - operator must specify a comment before save a call.")]) ?>
        <?= Html::submitButton(Yii::t('site', 'Add'), ['class' => 'btn btn-success pull-right']); ?>

    </p>
<?php



ActiveForm::end();

Pjax::begin(['id' => 'script___call_end_reason__list_grid', 'options' => ["url" => "/sosat/ebat"]]);

echo $this->render("_list_grid", compact("data_provider"));
 ?>
<?php Pjax::end();


Modal::end();