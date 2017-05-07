<?php

/* @var $this yii\web\View */
/* @var $model app\modules\script\models\ar\Field */
/* @var $form  yii\bootstrap\ActiveForm */
/* @var $form_id  string */
/* @var $modal_id  string */
/* @var $url  string */
/* @var $saved  boolean */

use yii\widgets\Pjax;
use app\modules\user\models\UserHeadManager;
use app\modules\script\models\ar\Field;
use yii\helpers\Url;

Pjax::begin(['id' => 'script___field__node_form_list_container', 'options' => ['url' => Url::to(['/script/field/node-form-list']), 'class' => 'btn-group'], 'enablePushState' => false]);

?>


    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="bottom: -1px">
        Вставить поле
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php
        $fields = Field::find()->byAccount(UserHeadManager::findHeadManagerByUser()->id)->all();

        if ($fields) {
            foreach ($fields as $field) {
                echo '<li><a class="insert-field" data-html="' . base64_encode($field->editorHtml()) . '">' . $field->name . '</a></li>';
            }
        }
        ?>
        <?php if (Yii::$app->getUser()->can('script___field__create')): ?>
            <li><a class="pjax-modal" href="/script/field/create" data-container="#script___field__create_ajax_modal_container" data-pjax="0">Создать поле</a></li>
        <?php endif; ?>
    </ul>
<?php

Pjax::end();