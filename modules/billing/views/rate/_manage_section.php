<?php
use yii\helpers\Html;

/** @var \app\modules\billing\models\Account $account */
?>
<li class="list-group-item">
    <strong><?php echo Yii::t("billing", "Pricing plan:") ?></strong> <?php echo $account->getRateLabel() ?>
    <?= Html::button(Yii::t('billing', 'Change'), [
        'class' => 'billing___rate__change_button btn btn-primary btn-xs pull-right',
        'data-toggle' => 'modal',
        'data-target' => '#billing___account__change_rate_modal',
        'disabled' => !Yii::$app->getUser()->can('billing__rate__change', ['account' => $account])
    ]) ?>
    <br/>
    <small>
        <?php if ($account->paid_till): ?>
            <?php echo Html::tag('strong', Yii::t("billing", "Paid till: {date}", ["date" => Yii::$app->getFormatter()->asDate($account->paid_till)])) ?>
            <br/>
        <?php endif; ?>

        <strong><?= Yii::t('billing', 'Monthly payment:') ?></strong> <?php echo Yii::t("billing", "{monthly_fee, number, currency}", ['monthly_fee' => $account->monthly_fee]) ?>

        <br/>

        <?php if (!$account->executions_per_day) : ?>
            <span class="text-success"><?= Yii::t('billing', 'Unlimited calls'); ?></span>
        <?php else: ?>
            <span class="text-danger">
                <?= Yii::t('billing', 'Calls limit:'); ?> <?php echo Yii::t("billing", "{n} calls per day", ['n' => $account->executions_per_day]) ?>, <?php echo Yii::t("billing", "{n} calls per month", ['n' => $account->executions_per_month]) ?>
            </span>
        <?php endif; ?>


        <br/>


        <?php if (!$account->operators_threshold) : ?>
            <span class="text-danger"><?= Yii::t('billing', 'You can not create user accounts'); ?></span>
        <?php else: ?>
            <span class="text-success">
                <?= Yii::t('billing', 'You can have'); ?>
                <?= Yii::t("billing", "{n, plural, =0{no operators} =1{# active operator} one{# active operator} few{# active operators} many{# active operators} other{# active operators}}", ['n' => $account->operators_threshold]) ?>
                <?= Yii::t('billing', 'max'); ?>
            </span>
        <?php endif; ?>

        <br/>


        <?php if (!$account->export_allowed) : ?>
            <span class="text-danger"><?= Yii::t('billing', 'Export of scripts is not allowed'); ?></span>
        <?php else: ?>
            <span class="text-success"><?= Yii::t('billing', 'Export of scripts is allowed'); ?></span>
        <?php endif; ?>


        <br/>
        <br/>


        <?php if ($account->is_trial) : ?>
            <span class="text-primary">
                * <?= Yii::t('billing', 'once you chane pricing plan - your FREE trail period will be canceled automatically'); ?><br/>
                ** <?= Yii::t('billing', 'once expired, your account will be switched to {plan} pricing plan automatically', ['plan' => $account->rate_name]); ?>
            </span>
        <?php endif; ?>
    </small>
</li>
