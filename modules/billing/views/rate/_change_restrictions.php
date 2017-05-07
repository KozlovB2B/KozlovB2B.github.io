<?php
use app\modules\billing\models\Rate;

/** @var Rate $rate */
?>

<small>
    <strong><?= Yii::t('billing', 'Monthly payment:') ?></strong> <?php echo Yii::t("billing", "{monthly_fee, number, currency}", ['monthly_fee' => $rate->monthly_fee]) ?>

    <br/>
    <?php if (!$rate->executions_per_day) : ?>
        <span class="text-success"><?= Yii::t('billing', 'Unlimited calls'); ?></span>
    <?php else: ?>
        <span class="text-danger">
                <?= Yii::t('billing', 'Calls limit:'); ?> <?php echo Yii::t("billing", "{n} calls per day", ['n' => $rate->executions_per_day]) ?>, <?php echo Yii::t("billing", "{n} calls per month", ['n' => $rate->executions_per_month]) ?>
            </span>
    <?php endif; ?>

    <br/>

    <?php if (!$rate->operators_threshold) : ?>
        <span class="text-danger"><?= Yii::t('billing', 'You can not create user accounts'); ?></span>
    <?php else: ?>
        <span class="text-success">
                <?= Yii::t('billing', 'You can have'); ?>
                <?= Yii::t("billing", "{n, plural, =0{no operators} =1{# active operator} one{# active operator} few{# active operators} many{# active operators} other{# active operators}}", ['n' => $rate->operators_threshold]) ?>
                <?= Yii::t('billing', 'max'); ?>
            </span>
    <?php endif; ?>
    <br/>
    <?php if (!$rate->export_allowed) : ?>
        <span class="text-danger"><?= Yii::t('billing', 'Export of scripts is not allowed'); ?></span>
    <?php else: ?>
        <span class="text-success"><?= Yii::t('billing', 'Export of scripts is allowed'); ?></span>
    <?php endif; ?>
</small>
