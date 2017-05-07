<?php
use yii\helpers\Html;
use app\modules\billing\components\AssetBundle;
use app\modules\billing\models\Account;

AssetBundle::register($this);

$this->registerJs("window['payment'] = new Payment(" . json_encode(['redirect_message' => Yii::t('billing', 'Now you are redirected to the payment page ...')]) . ");");
$this->registerJs("window['ba'] = new BillingAccount();");

$this->title = Yii::t('billing', 'Billing');
$this->params['breadcrumbs'][] = $this->title;

/** @var app\modules\billing\Module $module */
$module = \Yii::$app->controller->module;

/** @var \app\modules\billing\models\Account $account */
?>
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <?php
            $payment_status = Yii::$app->getRequest()->getQueryParam('payment');

            switch ($payment_status) {
                case 'success':
                    echo Html::tag('div', Yii::t('billing', 'Payment successfully received. Soon the money will be credited to your balance.'), ['class' => 'alert alert-success text-center']);
                    break;
                case 'fail':
                    echo Html::tag('div', Yii::t('billing', 'An error occurred during the payment.'), ['class' => 'alert alert-danger text-center']);
                    break;
                default:
                    break;
            }
            ?>
        </div>
    </div>
<?php
if (Account::isBlocked()) {
    echo Html::tag('div', Yii::t('billing', 'Your account has been suspended. To continue with the system - please replenish the balance on the account or set free rate.'), ['class' => 'alert alert-danger text-center']);
}
?>

    <div class="row">
        <div class="col-lg-4">
            <?php if ($account->is_trial) : ?>
                <div class="alert alert-success text-center" role="alert">
                    <?php echo Html::tag('strong', Yii::t("billing", "Free trial till: {date}", ["date" => Yii::$app->getFormatter()->asDate($account->trial_till)])) ?>
                </div>
            <?php endif; ?>
            <ul class="list-group">
                <li class="list-group-item">
                    <strong><?php echo Yii::t("billing", "Current balance:") ?></strong> <?php echo Yii::t("billing", "{balance, number, currency}", ["balance" => $account->currentBalance()]) ?>

                    <?php
                    if ($module->hasPaymentServices()) {
                        echo Html::button(Yii::t('billing', 'Buy credits'), [
                            'data-toggle' => 'modal',
                            'data-target' => '#billing___payment__pay_modal',
                            'class' => 'billing___account__top_up_balance_button btn btn-primary btn-xs pull-right'
                        ]);
                    } ?>
                    <br/> <br/>

                    <small class="text-primary">
                        <?php if ($account->is_trial || $account->monthly_fee == 0) : ?>
                            * <?= Yii::t('billing', 'During the trial period or a free rate - write-off does not occur.') ?>
                        <?php else: ?>
                            * <?= Yii::t('billing', 'Write-offs occur daily.') ?>
                        <?php endif; ?>
                    </small>
                </li>
                <?= $this->render("@app/modules/billing/views/bank-props/_manage_section", ['account' => $account]); ?>
                <?= $this->render("@app/modules/billing/views/rate/_manage_section", ['account' => $account]); ?>

            </ul>
        </div>
        <div class="col-lg-8">
            <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#billing___balance_operations__user_index_wrapper" id="billing___balance_operations__user_index_wrapper-tab" role="tab" data-toggle="tab" aria-controls="billing___balance_operations__user_index_wrapper" aria-expanded="true">
                            <?= Yii::t('billing', 'Balance operations') ?>
                        </a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#billing___rate_change_history__user_index_wrapper" role="tab" id="billing___rate_change_history__user_index_wrapper-tab" data-toggle="tab" aria-controls="billing___rate_change_history__user_index_wrapper" aria-expanded="false">
                            <?= Yii::t('billing', 'Pricing plans history') ?>
                        </a>
                    </li>
                    <?php if ($account->props) : ?>
                        <li role="presentation" class="">
                            <a href="#billing___invoice__user_index_wrapper" role="tab" id="billing___invoice__user_index_wrapper-tab" data-toggle="tab" aria-controls="billing___invoice__user_index_wrapper" aria-expanded="false">
                                <?= Yii::t('billing', 'Bills') ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="billing___balance_operations__user_index_wrapper" aria-labelledby="home-tab">
                        <br/>
                        <?= $this->render('@app/modules/billing/views/balance-operations/_user_list') ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="billing___rate_change_history__user_index_wrapper" aria-labelledby="billing___rate_change_history__user_index_wrapper-tab">
                        <br/>
                        <?= $this->render('@app/modules/billing/views/rate-change-history/_user_list') ?>
                    </div>
                    <?php if ($account->props) : ?>
                        <div role="tabpanel" class="tab-pane fade" id="billing___invoice__user_index_wrapper" aria-labelledby="billing___invoice__user_index_wrapper-tab">
                            <br/>
                            <?= $this->render('@app/modules/billing/views/invoice/_user_list') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php

// Модалы вставляются перед закрывающим тегом </body>
Yii::$app->controller->modals[] = $this->render("@app/modules/billing/views/payment/_pay_modal");
Yii::$app->controller->modals[] = $this->render("@app/modules/billing/views/account/_change_rate_modal", ['account' => $account]);