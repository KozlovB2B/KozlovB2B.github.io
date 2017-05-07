<?php

/* @var $this yii\web\View */
/* @var $model app\modules\script\models\ar\Release */
/* @var $form yii\bootstrap\ActiveForm */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;


Modal::begin([
    'header' => Html::tag('strong', 'Новая публикация'),
    'id' => 'script___release__create_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::button('Опубликовать', ['class' => 'btn btn-success core___functions__trigger_modal_form_submit']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

Pjax::begin(['id' => 'script___busy_time__form_container', 'enablePushState' => false]);

$form = ActiveForm::begin(['id' => 'script___busy_time__form', 'action' => '/script/release/create?id=' . $model->script_id, 'options' => ['data-pjax' => true]]);

if (!$model->getIsNewRecord()) {
    $this->registerJs('$("#script___release__create_modal").modal("hide")');
    $this->registerJs('reloadPjax("script___script__main_page_list_grid");');
    $this->registerJs('message("success", "Скрипт опубликован!");');
}

?>

    <div class="form-group">
        <label class="control-label">Скрипт:</label> #<?php echo $model->script->id . ' - ' . $model->script->name; ?>
    </div>

<?php if ($model->script->release): ?>
    <div class="form-group">
        <label class="control-label">Текущая публикация:</label> <?php echo $model->script->release->version . ' ' . ($model->script->release->name ? "(" . $model->script->release->name . ")" : null); ?>
    </div>
<?php endif; ?>

<?php

echo $form->field($model, 'name')->textInput([
    'rows' => 5,
    'placeholder' => 'Название публикации'])->hint('Любое название. Можно оставить пустым.', ['class' => 'help-hint small']);

echo $form->field($model, 'version')->textInput([
    'rows' => 5,
    'placeholder' => 'Версия'
])->hint('Вы можете назвать версию публикации как хотите.<br/><br/>Удобно именовать версии, например, так:  v1.0 или v2.3', ['class' => 'help-hint  small']);

?>
    <div class="small alert alert-info">
        Публикация будет доступна операторам.
        <br/>
        Они смогут совершать звонки по этой публикации.
    </div>
<?php
ActiveForm::end();

Pjax::end();

Modal::end();