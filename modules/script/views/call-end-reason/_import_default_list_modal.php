<?php
/**
 * @var app\modules\script\models\CallEndReason $model
 * @var \yii\data\ActiveDataProvider $data_provider
 * @var  \yii\web\View $this
 */
use yii\bootstrap\Modal;
use yii\helpers\Html;

Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Call end reasons')),
    'id' => 'script___call_end_reason__list_modal',
    'size' => Modal::SIZE_DEFAULT,
    'footer' => Html::submitButton(Yii::t('site', 'Import'), ['class' => 'btn btn-success', 'id' => 'script___call_end_reason__import_default_list_button']) . " " . Html::a(Yii::t('site', 'Close'), "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);
?>

    Вы еще не добавляле причин завершения звонка.<br/>
    Вы можете импортировать стандартный список причин:<br/>
<?php

foreach ($model->getDefaultReasonsList() as $r) {
    echo "- " . $r->name . ($r->comment_required ? " (коментарий обязателен)" : null) . "<br/>";
}
$model->createFirstDeletedReason();
?>
    <br/>
    <span class="text-danger">Сообщение не появится второй раз.</span>


<?php
Modal::end();