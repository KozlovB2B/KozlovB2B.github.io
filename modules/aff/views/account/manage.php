<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var app\modules\aff\models\Account $account
 * @var $this yii\web\View
 */
$this->title = Yii::t('aff', 'Affiliate program');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <?php if ($account->terms_accepted): ?>
            <h4 class="text-center well">
                <?= Yii::t('aff', 'Your affiliate link: {link}', ['link' => $account->getLink()]) ?>
            </h4>
        <?php endif; ?>
        <?= $this->render('_essence_list', ['account' => $account]) ?>

        <?php if (!$account->terms_accepted): ?>
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'text-center well'],
                'enableAjaxValidation' => false,
                'enableClientValidation' => false
            ]); ?>
            <p>
                <?= Yii::t('aff', 'Click the button, get your personal affiliate link and start advertising of Sales Script PROMPTER right now:') ?>
            </p>
            <br/>
            <?= $form->field($account, 'terms_accepted')->checkbox(['label' => Yii::t('aff', "I've read and agreed all the {link}", ['link' => Html::a(Yii::t('aff', 'affiliate program terms and conditions'), '/aff/terms', ['target' => '_blank'])])]) ?>
            <br/>
            <?= Html::submitButton(Yii::t('aff', 'Give me my affiliate link'), ['class' => 'btn btn-success btn-lg']) ?>

            <?php ActiveForm::end(); ?>
        <?php endif; ?>

        <?php if ($account->affiliate_id): ?>
            <p class="text-center">
                <?= Yii::t('aff', 'You are logged in at the invitation of the user: {username}', ['username' => $account->affiliate->username]) ?>
            </p>
        <?php endif; ?>
    </div>
</div>