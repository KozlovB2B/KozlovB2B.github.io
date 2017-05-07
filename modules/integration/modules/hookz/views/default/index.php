<?php
use yii\helpers\Html;
use app\modules\core\components\widgets\GlyphIcon;
use app\modules\core\components\Url;

/* @var $this yii\web\View */
/* @var boolean $enabled */


$this->title = Yii::t('hookz', 'WebHooks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('integration', 'Integrations'), 'url' => '/integration'];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
setEvent("change", "#integration___hookz__enable", function () {
    if($(this).prop("checked")){
        ajax("/integration/enabled-list/enable?module=hookz");
    } else {
        ajax("/integration/enabled-list/disable?module=hookz");
    }

    return true;
});');

?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <fieldset>
                <legend class="buttons text-right">
                    <span class="pull-left">
                   <label>
                       <?php echo Html::checkbox('enable', $enabled, ['id' => 'integration___hookz__enable', 'class' => 'big-checkbox']); ?> Включить WebHooks
                   </label>
                    </span>

                    <?php echo Html::a(GlyphIcon::i('plus') . ' ' . Yii::t('hookz', 'Create WebHook'), Url::to(["/integration/hookz/hook/create"]), ['data-container' => '#integration___hookz___create_hook_modal_pjax', 'class' => 'btn btn-primary btn-xs pjax-modal', 'data-pjax' => 0]); ?>
                </legend>
            </fieldset>
            <?php echo $this->render('/hook/_list') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <fieldset>
                <legend class="buttons "><?php echo Yii::t('hookz', 'Little documentation') ?></legend>
            </fieldset>
            <?php echo $this->render('/hook/_doc') ?>
        </div>
    </div>
</div>

