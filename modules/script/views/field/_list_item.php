<?php
use yii\helpers\Html;
use app\modules\core\components\widgets\GlyphIcon;

/* @var $this yii\web\View */
/* @var int $index */
/* @var $model app\modules\script\models\ar\Field */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-xs-6">
    <?php echo $model->name ?>
    <br/>
    <i class="small">
        <?php echo $model->code ?>
    </i>
</div>
<div class="col-xs-4"><?php echo $model->getTypeName() ?></div>
<div class="col-xs-2 text-right">
    <?php Html::a(GlyphIcon::i('pencil'), '/script/field/update?id=' . $model->id, [
        'title' => Yii::t('yii', 'Update'),
        'class' => 'pjax-modal',
        'data-container' => '#script___field__update_ajax_modal_container',
        'aria-label' => Yii::t('yii', 'Update'),
        'data-pjax' => '0',
    ]); ?>

    <?php echo Html::a(GlyphIcon::i('trash'), '/script/field/delete?id=' . $model->id, [
        'title' => Yii::t('yii', 'Delete'),
        'class' => 'pjax-delete',
        'data-pjax' => '0'
    ]); ?>
</div>