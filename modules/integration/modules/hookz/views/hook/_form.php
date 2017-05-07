<?php
use yii\widgets\ActiveForm;
use app\modules\integration\modules\hookz\components\HookEvent;
use app\modules\integration\modules\hookz\components\WebHookPerformer;

/* @var $this yii\web\View */
/* @var $model app\modules\integration\modules\hookz\models\Hook */
/* @var $url string */
/* @var $url $type */
?>
<?php
$form = ActiveForm::begin([
    'id' => 'integration___hookz___' . $type . '_hook_form',
    'action' => $url,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'options' => ['data-pjax' => true]
]); ?>
    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'event')->dropDownList(HookEvent::getList()); ?>
            <?= $form->field($model, 'get')->textarea(['rows' => 3]); ?>

        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'post')->textarea(['rows' => 7]); ?>
        </div>
    </div>

    <h5>Доступные маркеры:</h5>
    <table class="table table-striped small">
        <tr>
            <th>Для URL</th>
            <th>Для POST</th>
            <th></th>
        </tr>
        <?php foreach (WebHookPerformer::getMarkers() as $marker => $data): ?>
            <tr>
                <td><?= $data['get'] ? "<strong>$marker</strong>" : null ?></td>
                <td><strong>"<?= $marker ?>"</strong></td>
                <td><?= $data['description'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php

ActiveForm::end();
