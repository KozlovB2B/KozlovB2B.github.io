<?php
use app\modules\integration\modules\onlinepbx\components\ApiCredentialsAssetBundle;
use app\modules\core\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\integration\modules\onlinepbx\models\ApiCredentials */


$this->title = Yii::t('onlinepbx', 'Online PBX integration');
$this->params['breadcrumbs'][] = ['label' => Yii::t('integration', 'Integrations'), 'url' => '/integration'];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container">
    <div class="row">
        <div class="col-xs-4">
            <fieldset>
                <legend><?php echo Yii::t('onlinepbx', 'Account Online PBX') ?></legend>
                <?php echo $this->render('/api-credentials/update_form', ['model' => $model]) ?>
            </fieldset>
        </div>
        <div class="col-xs-8">
            <fieldset>
                <legend><?php echo Yii::t('onlinepbx', 'Users settings') ?></legend>
                <?php echo $this->render('/user-settings/index') ?>
            </fieldset>
        </div>
    </div>
</div>

