<?php
/* @var $this \yii\web\View */
/* @var $content string */
// http://adcollider.ru/user/user/login-using-key?id=11&key=M2sOUbhGCOyifw1te-SOMcWrrqqM6a7x

use app\modules\core\components\widgets\GlyphIcon;
use yii\helpers\Url;
use app\modules\site\components\CompanyStyleAssetBundle;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use app\modules\site\components\assets\V2DesignAsset;
use app\modules\script\components\assets\PerformerAsset;
use app\modules\core\components\CoreAssetBundle;
use yii\widgets\PjaxAsset;
use app\modules\billing\models\Account as BillingAccount;
use app\modules\site\models\EmailServices;
use app\modules\script\components\assets\ContextAsset;

$company_style = CompanyStyleAssetBundle::register($this);
V2DesignAsset::register($this);
PerformerAsset::register($this);
ContextAsset::register($this);
CoreAssetBundle::register($this);
PjaxAsset::register($this);
\yii\bootstrap\BootstrapAsset::register($this);
\yii\bootstrap\BootstrapPluginAsset::register($this);

$hm = \app\modules\user\models\UserHeadManager::findHeadManagerByUser();

$this->registerJs('new V2Design();');
$this->registerJs('(new YiijWebApplication({})).run();', \yii\web\View::POS_END);

$this->registerJs("
Yiij.app.setModule('context', {
    'constructor' : Context
});

Yiij.app.getModule('context').start();


Yiij.app.setModule('performer', {
    'constructor' : Performer,
    'recorder' : {
        'enable' :  " . (int)$hm->record_calls . ",
        'key' :  '" . Yii::$app->getUser()->getIdentity()->getAuthKey() . "',
        'host' : '" . str_replace('http', 'ws', Url::to('/recorder', true)) . "'
    },
    'account' : " . $hm->id . "
});

Yiij.app.getModule('performer').start();

window['_csrf'] = '" . Yii::$app->getRequest()->csrfToken . "';
");

$menu_items = [
    ['label' => Yii::t("site", 'Dashboard'), 'visible' => Yii::$app->getUser()->can("user___head__dashboard"), 'url' => '/dashboard'],
    [
        'label' => Yii::t("script", 'Reports'),
        'visible' => Yii::$app->getUser()->can("script___report__view"),
        'url' => '#',
        'items' => [
            ['label' => Yii::t("script", 'Scripts'), 'visible' => Yii::$app->getUser()->can("script___report__view"), 'url' => '/script/report/by-scripts'],
            ['label' => Yii::t("script", 'Calls'), 'visible' => Yii::$app->getUser()->can("script___report__view"), 'url' => '/script/report/by-calls'],
            ['label' => Yii::t("script", 'Variants usage'), 'visible' => Yii::$app->getUser()->can("script___hits_report__view"), 'url' => '/script/report/variants'],

        ],
    ],
    [
        'label' => Yii::t("site", 'Help'),
        'visible' => Yii::$app->getUser()->can("site___instruction__view"),
        'url' => '#',
        'items' => [
            ['label' => Yii::t("site", 'Main manual'), 'visible' => Yii::$app->getUser()->can("site___instruction__view"), 'url' => '/manual'],
            ['label' => Yii::t("site", 'Video'), 'visible' => Yii::$app->getUser()->can("site___instruction__view"), 'url' => '/instructions'],
            ['label' => Yii::t("site", 'FAQ'), 'visible' => Yii::$app->getUser()->can("site___instruction__view"), 'url' => '/faq'],
            ['label' => Yii::t("site", 'Contact us'), 'visible' => Yii::$app->getUser()->can("site___instruction__view"), 'url' => '/contact'],
        ],
    ],
    ['label' => Yii::t("integration", 'Integrations'), 'visible' => Yii::$app->getUser()->can("integration___integration__manage"), 'url' => '/integration'],
    ['label' => Yii::t("billing", 'Payment'), 'visible' => Yii::$app->getUser()->can("billing___account__manage_own"), 'url' => '/billing'],
    [
        'label' => "<i class='glyphicon glyphicon-cog'></i>",
        'visible' => Yii::$app->getUser()->can("aff___account__manage_own"),
        'url' => '#',
        'items' => [
            ['label' => Yii::t('site', 'Settings'), 'url' => ['/profile']],
            ['label' => Yii::t("script", 'Fields'), 'visible' => Yii::$app->getUser()->can("script___field__create"), 'url' => ['/script/field/index']],
            ['label' => Yii::t('site', 'Billing info'), 'url' => ['/billing']],
            ['label' => Yii::t('aff', 'Affiliate program'), 'visible' => Yii::$app->getUser()->can("aff___account__manage_own"), 'url' => ['/aff']]
        ],
    ]
];

Yii::$app->controller->func_panel[] = Html::tag('h3', Html::encode($this->title), ['id' => 'page-title']);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= $company_style->faviconHtml() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
<div id="menu-wrap">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-8 col-xs-8" id="menu-left-col">
                <div id="brand">
                    <div class="brand visible-lg visible-md hidden-sm hidden-xs">
                        <?= Html::a(Html::img('/static/favicon/android-icon-48x48.png') . ' <span>ScriptDesigner</span>', '/', ['class' => 'qtipped  brand-name', "title" => 'К панели управления']) ?>
                    </div>
                    <div class="brand brand-small hidden-lg hidden-md visible-sm visible-xs">
                        <?= Html::a(Html::img('/static/favicon/android-icon-48x48.png') . ' ScriptDesigner', '/', ['class' => 'qtipped brand-name', "title" => 'К панели управления',]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="user-info col-xs-12">
                        <?php

                        if (!Yii::$app->user->isGuest) {

                            echo '<br/>';
                            echo Yii::$app->user->identity->username . ' ' . Html::a(Yii::t("user", 'Logout'), Url::to(['/user/user/logout']));

                            echo '<br/>';
                            echo '<br/>';

                            if (BillingAccount::current()) {
                                echo Html::a(BillingAccount::current()->getMenuIndicator(), '/billing', ['class' => 'label-link']);
                            }
                        }

                        ?>
                    </div>
                </div>

                <div class="copyright-btm">
                    ScriptDesigner.ru
                    <br/>
                    <?= Html::a('Блог', '/blog', ['target' => '_blank']); ?>
                    <br/>
                    <?= Html::a('Связаться с нами', '/site/site/contact-modal', ['class' => 'pjax-modal', 'data-container' => '#site___site__contact_modal_pjax']); ?>
                    <br/>
                    <?= Yii::$app->getUser()->can('aff___account__manage_own') ? Html::a(Yii::t('aff', 'Affiliate program'), '/aff') : null; ?>
                </div>

                <div class="row hide visible-xs visible-sm">
                    <div class="navbar-collapse collapse in ">
                        <?php
                        //                        echo Nav::widget([
                        //                            'id' => 'main-navigation-left',
                        //                            'options' => ['class' => 'nav  navbar-nav nav-pills nav-stacked main-navigation main-navigation-left'],
                        //                            'encodeLabels' => false,
                        //                            'items' => $menu_items
                        //                        ]);
                        ?>
                    </div>
                </div>

            </div>
            <div class="col-lg-10 col-md-9 col-sm-9 hidden-sm hidden-xs">
                <?= Nav::widget([
                    'id' => 'main-navigation-top',
                    'options' => ['class' => 'nav nav-pills main-navigation main-navigation-top'],
                    'encodeLabels' => false,
                    'items' => $menu_items
                ]); ?>
            </div>
            <div class="hide visible-xs visible-sm col-sm-4 col-xs-4" id="menu-right-col">
                <?php echo Html::a(GlyphIcon::i('log-out'), Url::to(["/logout"]), ['class' => 'qtipped', 'title' => Yii::t('site', 'Logout')]) ?>
            </div>
        </div>
    </div>
</div>
<div id="func-panel">
    <span class="func-button" id="func-panel-toggle-menu">
        <?= GlyphIcon::i('menu-hamburger') ?>
    </span>

    <?php echo implode(' ', Yii::$app->controller->func_panel) ?>

    <div class="pull-right hidden-xs">

        <?= Nav::widget([
            'id' => 'main-navigation-func-panel',
            'options' => ['class' => 'nav nav-pills main-navigation main-navigation-func-panel'],
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => "<i class='glyphicon glyphicon-cog'></i>",
                    'visible' => Yii::$app->getUser()->can("billing___account__manage_own"),
                    'url' => '#',
                    'dropDownOptions' => ['class' => 'dropdown-menu-right'],
                    'items' => [

                        ['label' => Yii::t('site', 'Settings'), 'url' => ['/profile']],
                        ['label' => Yii::t("script", 'Fields'), 'visible' => Yii::$app->getUser()->can("script___field__create"), 'url' => ['/script/field/index']],

                        ['label' => Yii::t('site', 'Billing info'), 'url' => ['/billing']],
                        ['label' => Yii::t('aff', 'Affiliate program'), 'visible' => Yii::$app->getUser()->can("aff___account__manage_own"), 'url' => ['/aff']]
                    ],
                ],
                ['label' => Yii::t("site", 'Manual'), 'visible' => Yii::$app->getUser()->can("site___instruction__view"), 'url' => '/manual'],
                [
                    'label' => Yii::t("script", 'Reports'),
                    'visible' => Yii::$app->getUser()->can("script___report__view"),
                    'url' => '#',
                    'items' => [
                        ['label' => Yii::t("script", 'Scripts'), 'visible' => Yii::$app->getUser()->can("script___report__view"), 'url' => '/script/report/by-scripts'],
                        ['label' => Yii::t("script", 'Calls'), 'visible' => Yii::$app->getUser()->can("script___report__view"), 'url' => '/script/report/by-calls'],
                    ],
                ],
                ['label' => Yii::t("site", 'Dashboard'), 'visible' => Yii::$app->getUser()->can("user___head__dashboard"), 'url' => '/dashboard'],
                ['label' => Yii::t("site", 'Dashboard'), 'visible' => Yii::$app->getUser()->can("user___designer__dashboard"), 'url' => '/designer-dashboard'],


            ]
        ]); ?>
    </div>
</div>
<div id="content-wrap">
    <div id="content" class="container-fluid">
        <?php if (!Yii::$app->getUser()->getIdentity()->isConfirmed): ?>
            <div class="alert alert-success text-center">
                <?php
                if ($service = EmailServices::recognizeService(Yii::$app->getUser()->getIdentity()->email)):
                    echo Yii::t('site', 'Please, prove you’re not a robot. Please check your {mailbox} and confirm.', ['mailbox' => Html::a($service->name, $service->url, ['target' => '_blank'])]);
                else :
                    echo Yii::t('site', 'Please, prove you’re not a robot. Please check your mailbox and confirm.');
                endif;
                ?>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>
<?= $this->render("@app/modules/site/views/layouts/_widget") ?>
<?= YII_ENV == 'prod' ? $this->render("@app/modules/site/views/layouts/_metrics") : null; ?>
<?php echo implode("\n", Yii::$app->controller->modals) ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
