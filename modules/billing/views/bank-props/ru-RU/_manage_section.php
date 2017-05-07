<?php
use yii\helpers\Html;
/** @var \app\modules\billing\models\Account $account */
?>
<li class="list-group-item">
    <strong><?php echo Yii::t("billing", "Your props:") ?></strong> <?php echo $account->props ? $account->props->company_name : Yii::t("billing", "not set") ?>

    <?= Html::button(Yii::t('billing', 'Edit props'), [
        'id' => 'billing___bank_props__edit_button',
        'class' => 'billing___account__top_up_balance_button btn btn-primary btn-xs pull-right'
    ]) ?>
    <br/><br/>

    <small class="text-primary">
        * Если ваши реквизиты юр. лица заполнены - вы можете пополнять счет через выписывание счетов
    </small>
</li>