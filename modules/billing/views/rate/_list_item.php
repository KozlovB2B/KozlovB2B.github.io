<?php
use \yii\helpers\Html;

/**
 * @var app\modules\billing\models\Rate $model
 */
?>
<div class="rate-content <?php echo($model->monthly_fee == 0 ? 'default' : null) ?>">
    <?php echo Html::tag('h4', $model->name, ['class' => 'rate-name']) ?>
    <h5 class="rate-price"><strong>
            <?php
            $fmt = new NumberFormatter($model->division, NumberFormatter::CURRENCY);
            $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, $model->currency);
            $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
            echo $fmt->formatCurrency($model->monthly_fee, $model->currency);
            ?>/<?php echo Yii::t('billing', 'mo') ?>
        </strong></h5>

    <?php

    $users = $model->operators_threshold + 1;

    echo Html::tag('p', Yii::t('billing', '{n, plural, =0{one user} =1{# operator} one{# operator} few{# operators} many{# operators} other{# operators}}', ['n' => $users]), ['class' => 'rate-restriction']);


    if ($model->executions_per_month) {
        echo Html::tag('p', Yii::t('billing', '{n} calls per month', ['n' => $model->executions_per_month]), ['class' => 'rate-restriction text-danger strong']);
    }else{
        echo Html::tag('p', Yii::t('billing', 'no limits'), ['class' => 'text-success strong']);
    }
    ?>
</div>