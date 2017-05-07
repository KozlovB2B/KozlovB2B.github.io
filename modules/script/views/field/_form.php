<?php

/* @var $this yii\web\View */
/* @var $model app\modules\script\models\ar\Field */
/* @var $form  yii\bootstrap\ActiveForm */
/* @var $form_id  string */
/* @var $modal_id  string */
/* @var $url  string */
/* @var $saved  boolean */

use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use app\modules\script\models\ar\Field;

Pjax::begin(['id' => $form_id . '_container', 'enablePushState' => false]);

$this->registerJs('
$("body").on("change", "#' . $form_id . '_type", function(){
    if($(this).val() == "in"){
        $("#' . $form_id . '_type_data_wrapper").show();
    }else{
        $("#' . $form_id . '_type_data_wrapper").hide();
    }
});');

if ($saved) {
    $this->registerJs('$("#' . $modal_id . '").modal("hide")');
    $this->registerJs('reloadPjax("script___field__index", "/script/field/index");');
    $this->registerJs('reloadPjax("script___field__node_form_list_container");');
    $this->registerJs('message("success", "' . Yii::t('site', 'Saved!') . '");');
}

$form = ActiveForm::begin(['id' => $form_id, 'action' => $url, 'options' => ['data-pjax' => true]]);

?>

<?= $form->field($model, 'name')->textInput() ?>
<?= $form->field($model, 'code')->textInput()->hint('Придумайте кодовое обозначение (лучше на латинице)', ['class' => 'help-hint small']) ?>
<?= $form->field($model, 'type')->dropDownList(Field::typesList(), ['id' => $form_id . '_type']) ?>

    <div id="<?= $form_id . '_type_data_wrapper' ?>" style="<?= $model->type == 'in' ? '' : 'display:none' ?>">
        <?= $form->field($model, 'type_data')->textInput(['id' => $form_id . '_type_data'])->hint('Задайте варианты для списка через запятую. Например: Да, Нет, Возможно.
        Можно так же задвать пары ключ:значение через запятую где ключ отделен от значения двоеточием. Например: 1:Да, 0:Нет, 0.5:Возможно', ['class' => 'help-hint small']) ?>
    </div>
<?php

ActiveForm::end();

Pjax::end();