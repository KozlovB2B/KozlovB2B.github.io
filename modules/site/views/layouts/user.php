<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\modules\core\components\CoreAssetBundle;
use rmrevin\yii\fontawesome\AssetBundle as FontAwesomeAssetBundle;
use app\modules\billing\models\Account as BillingAccount;


CoreAssetBundle::register($this);
FontAwesomeAssetBundle::register($this);
$this->registerJs("window['app']['loading_message'] = '" . Yii::t('site', 'Loading') . "'");
$this->registerJs("window['app']['saving_message'] = '" . Yii::t('site', 'Saving') . "'");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?= $this->render("_favicon"); ?>
</head>
<body>
<?php echo $this->render('_service'); ?>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    \Yii::$app->getModule('script');
    \Yii::$app->getModule('billing');
    \Yii::$app->getModule('aff');
    \Yii::$app->getModule('integration');

    //echo Yii::$app->getUser()->can('user_head_manager');


    NavBar::begin([
        'brandLabel' => Html::img('/static/favicon/android-icon-48x48.png'),
        'brandUrl' => Yii::$app->homeUrl,
        'innerContainerOptions' => ['class' => 'container-fluid']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => [
            [
                'label' => Yii::t("script", 'Reports'),
                'visible' => Yii::$app->getUser()->can("script___report__view"),
                'url' => '#',
                'items' => [
                    ['label' => Yii::t("script", 'Scripts'), 'visible' => Yii::$app->getUser()->can("script___report__view"), 'url' => '/script/report/by-scripts'],
                    ['label' => Yii::t("script", 'Calls'), 'visible' => Yii::$app->getUser()->can("script___report__view"), 'url' => '/script/report/by-calls'],
                ],
            ],
            [
                'label' => Yii::t("site", 'Help'),
                'visible' => Yii::$app->getUser()->can("site___instruction__view"),
                'url' => '#',
                'items' => [
                    ['label' => Yii::t("site", 'Video'), 'visible' => Yii::$app->getUser()->can("site___instruction__view"), 'url' => '/instructions'],
                    ['label' => Yii::t("site", 'FAQ'), 'visible' => Yii::$app->getUser()->can("site___instruction__view"), 'url' => '/faq'],
                    ['label' => Yii::t("site", 'Contact us'), 'visible' => Yii::$app->getUser()->can("site___instruction__view"), 'url' => '/contact'],
                ],
            ],
            ['label' => Yii::t("integration", 'Integrations'), 'visible' => Yii::$app->getUser()->can("integration___integration__manage"), 'url' => '/integration'],
//            ['label' => Yii::t("script", 'SIP Accounts'), 'visible' => Yii::$app->getUser()->can("script___sip_account__manage_children"), 'url' => '/sip-accounts'],
//            ['label' => Yii::t("script", 'SIP Account'), 'visible' => !Yii::$app->getUser()->can("script___sip_account__manage_children") && Yii::$app->getUser()->can("script___sip_account__manage_own"), 'url' => '/sip-account'],

//            [
//                'label' => Yii::t("integration", 'Integrations'),
//                'visible' => Yii::$app->getUser()->can("integration___integration__manage"),
//                'url' => '#',
//                'items' => [
//                    ['label' => Yii::t("integration", 'Amo CRM'), 'visible' => Yii::$app->getUser()->can("integration___integration__manage"), 'url' => '/integration/amo'],
//                ],
//            ],
        ],
    ]);


    $right_items = [];

    if (Yii::$app->user->isGuest) {
        $right_items[] = ['label' => Yii::t("user", 'Sign in'), 'url' => ['/user/login']];
    } else {
        /** @var BillingAccount $billing */
        $billing = BillingAccount::findOne(Yii::$app->user->getId());
        if ($billing) {
            $right_items[] = [
                'label' => $billing->getMenuIndicator(),
                'url' => ['/billing'],
                'encode' => false
            ];
            $right_items[] = [
                'label' => Yii::t("site", 'Account'),
                'url' => '#',
                'items' => [
                    ['label' => Yii::t('site', 'Billing info'), 'url' => ['/billing']],
                    ['label' => Yii::t('site', 'Settings'), 'url' => ['/profile']],
                    ['label' => Yii::t('aff', 'Affiliate program'), 'url' => ['/aff']],
                    ['label' => Yii::t('site', 'Operators'), 'url' => ['/operators']]
                ],
            ];
        }

        $right_items[] = [
            'label' => Yii::t("user", 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
            'url' => ['/user/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];

    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $right_items
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]); ?>
        <?= $this->render("@app/modules/site/views/site/_flash"); ?>
        <?= $content ?>
    </div>


</div>

<?= $this->render("_metrics"); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
