<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\modules\core\components\CoreAssetBundle;
use rmrevin\yii\fontawesome\AssetBundle as FontAwesomeAssetBundle;


CoreAssetBundle::register($this);
FontAwesomeAssetBundle::register($this);
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
    <!--    <link rel="stylesheet" href="/css/lumen.bootstrap.css">-->
</head>
<body>
<?php $this->beginBody() ?>
<?= $this->render("_service"); ?>


<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('/static/favicon/android-icon-48x48.png'),
        'brandUrl' => Yii::$app->homeUrl,
        'innerContainerOptions' => ['class' => 'container-fluid']
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => [
            ['label' => Yii::t("site", 'Instructions'), 'visible' => Yii::$app->getUser()->can("site___instruction__manage"), 'url' => ['/site/instruction/manage',]],
            ['label' => Yii::t("user", 'Users'), 'visible' => Yii::$app->getUser()->can("user___account__manage"), 'url' => ['/site/users/admin',]],

            [
                'label' => "Продажи",
                'visible' => Yii::$app->getUser()->can("sales___access"),

                'items' => [
                    ['label' => 'Клиенты', 'url' => '/sales/user-stat/index'],
                    ['label' => 'Попытки экспорта скриптов', 'visible' => Yii::$app->getUser()->can("script___script_export_log__index"), 'url' => '/script/script-export-log/index']
                ]
            ],
            ['label' => Yii::t("billing", 'Billing'), 'items' => [
                ['label' => 'Тарифы', 'visible' => Yii::$app->getUser()->can("billing___rate__manage"), 'url' => ['/billing/rate/manage',]],
                ['label' => 'Счета', 'visible' => Yii::$app->getUser()->can("billing___invoice__manage"), 'url' => ['/billing/invoice/admin',]],
                ['label' => 'Cronopay нотификации', 'visible' => Yii::$app->getUser()->can("billing___balance_operations__index_all"), 'url' => ['/billing/balance-operations/cronopay-notifications',]],
                ['label' => 'Инвойсы PayPal', 'visible' => Yii::$app->getUser()->can("billing___balance_operations__index_all"), 'url' => ['/billing/balance-operations/paypal-invoice',]],
                ['label' => 'Процедуры списания', 'visible' => Yii::$app->getUser()->can("billing___use_withdraw__index"), 'url' => ['/billing/use-withdraw/index',]],
                ['label' => 'Операции по балансу', 'visible' => Yii::$app->getUser()->can("billing___balance_operations__index_all"), 'url' => ['/billing/balance-operations/index',]],
                ['label' => 'Отчет по списанимя с клиентов', 'visible' => Yii::$app->getUser()->can("billing___balance_operations__cashflow_report"), 'url' => ['/billing/balance-operations/cashflow-report',]],
                ['label' => 'История смены тарифов', 'visible' => Yii::$app->getUser()->can("billing___rate_change_history__index_all"), 'url' => ['/billing/rate-change-history/index',]],
            ]],
            [
                'label' => 'Блог',
                'visible' => Yii::$app->getUser()->can("blog___blog__admin"),
                'items' => [
                    ['label' => 'Авторы', 'visible' => Yii::$app->getUser()->can("blog___blog__admin"), 'url' => '/blog/author/index'],
                    ['label' => 'Посты', 'visible' => Yii::$app->getUser()->can("blog___blog__admin"), 'url' => '/blog/post/index'],
//                    ['label' => 'Тур по системе', 'visible' => Yii::$app->getUser()->can("blog___blog__admin"), 'url' => '/blog/tour/admin']
                ]
            ],
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            Yii::$app->user->isGuest ?
                ['label' => Yii::t("user", 'Sign in'), 'url' => ['/user/login']] :
                [
                    'label' => Yii::t("user", 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/user/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
