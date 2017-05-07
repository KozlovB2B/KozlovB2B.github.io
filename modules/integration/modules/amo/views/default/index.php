<?php
/* @var $this yii\web\View */
/* @var $head app\modules\integration\modules\amo\models\Amouser */

$this->title = Yii::t('amo', 'Amo integration');
$this->params['breadcrumbs'][] = ['label' => Yii::t('integration', 'Integrations'), 'url' => '/integration'];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="container">
    <div class="row">
        <div class="col-xs-4">
            <fieldset>
                <legend><?php echo Yii::t('amo', 'AmoCRM admin account') ?></legend>
                <?php echo $this->render('/user/_update_form', ['model' => $head, 'saved' => false]) ?>
            </fieldset>
            <br/>
            <p class="small">
<!--               Инструкция как работает интеграция с AmoCRM <a href="https://scriptdesigner.ru/blog/integraciya-s-oblacnymi-crm-sistemami" target="_blank">scriptdesigner.ru/blog/integraciya-s-oblacnymi-crm-sistemami</a>-->
            </p>
        </div>
        <div class="col-xs-8">
            <fieldset>
                <legend><?php echo Yii::t('amo', 'Mangers') ?></legend>
                <?php echo $this->render('/user/index', ['head' => $head]) ?>
            </fieldset>
        </div>
    </div>
</div>

