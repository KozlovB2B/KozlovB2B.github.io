<?php
use app\modules\integration\modules\onlinepbx\components\ApiCredentialsAssetBundle;
use app\modules\core\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\integration\modules\onlinepbx\models\ApiCredentials */

ApiCredentialsAssetBundle::register($this);
$this->registerJs("window['api-credentials'] = new ApiCredentials();");

?>
<?php $form = ActiveForm::begin([
    'id' => 'integration___onlinepbx__api_credentials_create_form',
    'action' => '/integration/onlinepbx/api-credentials/update',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>
<?= $form->field($model, 'domain')->textInput()->hint(Html::hint(Yii::t('onlinepbx', 'Ваш домен аккаунта в Online PBX. Например: example.onpbx.ru'))); ?>
<?= $form->field($model, 'key')->textInput()->hint(Html::hint(Yii::t('onlinepbx', 'Ключ API вы можете посмотреть в разделе Настройки личного кабинета Online PBX.'))); ?>
<?= Html::submitButton(Yii::t('integration', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
<?php ActiveForm::end(); ?>