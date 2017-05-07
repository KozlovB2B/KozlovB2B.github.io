<?php
/* @var $this yii\web\View */
/* @var $model app\modules\integration\modules\zebra\models\ApiCredentials */


$this->title = Yii::t('zebra', 'Zebra integration');
$this->params['breadcrumbs'][] = ['label' => Yii::t('integration', 'Integrations'), 'url' => '/integration'];
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="container">
    <div class="row">
        <div class="col-xs-4">
            <fieldset>
                <legend><?php echo Yii::t('zebra', 'Account Zebra') ?></legend>
                <?php echo $this->render('/api-credentials/_update_form', ['model' => $model, 'saved' => false]) ?>
            </fieldset>
        </div>
        <div class="col-xs-8">
            <fieldset>
                <legend><?php echo Yii::t('zebra', 'Users settings') ?></legend>
                <?php echo $this->render('/user-settings/index') ?>
            </fieldset>
        </div>
    </div>
</div>

