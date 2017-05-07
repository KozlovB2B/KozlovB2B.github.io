<?php
use app\modules\core\helpers\Html;

use yii\widgets\Pjax;

use yii\bootstrap\Modal;

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $saved boolean */
/* @var $model app\modules\integration\modules\hookz\models\Hook */

Modal::begin([
    'header' => Html::tag('h4', 'Редактировать WebHook'),
    'id' => 'integration___hookz___update_hook_modal',
    'size' => Modal::SIZE_LARGE,
    'footer' => Html::button('Сохранить', ['class' => 'btn btn-success core___functions__trigger_modal_form_submit']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

Pjax::begin(['id' => 'integration___hookz___update_hook_modal_container', 'enablePushState' => false]);

if ($saved) {
    $this->registerJs('$("#integration___hookz___update_hook_modal").modal("hide")');
    $this->registerJs('reloadPjax("integration___hookz__hook_grid");');
    $this->registerJs('message("success", "' . Yii::t('integration', 'Saved!') . '");');
}

echo $this->render('_form', ['model' => $model, 'type' => 'update', 'url' => Url::to(['/integration/hookz/hook/update', 'id' => $model->id])]);

Pjax::end();

Modal::end();