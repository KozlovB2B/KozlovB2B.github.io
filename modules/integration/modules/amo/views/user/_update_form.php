<?php
use app\modules\core\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $saved boolean */
/* @var $model app\modules\integration\modules\amo\models\AmoUser */

Pjax::begin(['id' => 'integration___amo__user_update_form_container', 'enablePushState' => false]);

if ($saved) {
    $this->registerJs('message("success", "' . Yii::t('integration', 'Saved!') . '");');
}

$form = ActiveForm::begin([
    'id' => 'integration___amo__user_update_form',
    'action' => '/integration/amo/user/update?id=' . $model->user_id,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'options' => ['data-pjax' => true]
]); ?>

<?= $form->field($model, 'subdomain')->textInput()->hint(Html::hint(Yii::t('amo', 'Адрес личного кабинета в Amo CRM имеет вид: <br/><i class="strong">поддомен</i>.amocrm.ru &mdash; укажите <i class="strong">поддомен</i> в этом поле.'))); ?>
<?= $form->field($model, 'amouser')->textInput()->hint(Html::hint(Yii::t('amo', 'Имя пользователя вашего админ. аккаунта Amo CRM.'))); ?>
<?= $form->field($model, 'amohash')->textInput()->hint(Html::hint(Yii::t('amo', 'Ключ API вы можете посмотреть в разделе API личного кабинета Amo CRM.'))); ?>

<?= Html::submitButton(Yii::t('integration', 'Save'), ['class' => 'btn btn-success pull-right']) ?>

<?php

ActiveForm::end();

Pjax::end();