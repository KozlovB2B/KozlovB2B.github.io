<?php
use kartik\sidenav\SideNav;
use app\modules\core\components\Url;
use app\modules\aff\models\Account;

$this->beginContent('@app/modules/user/views/head/layout.php'); ?>
    <div class="row">
        <div class="col-xs-2">
            <?= SideNav::widget([
                'encodeLabels' => false,
                'heading' => false,
                'items' => [
                    // Important: you need to specify url as 'controller/action',
                    // not just as 'controller' even if default action is used.
                    ['label' => Yii::t('aff', 'Essence of contract'), 'url' => '/aff', 'active' => Url::isActive('/aff')],
                    ['label' => Yii::t('aff', 'Promo links'), 'url' => '/aff/promo-links', 'active' => Url::isActive('/aff/promo-links'), 'visible' => Account::current()->terms_accepted],
                    ['label' => Yii::t('aff', 'Hits'), 'url' => '/aff/hits', 'active' => Url::isActive('/aff/hits'), 'visible' => Account::current()->terms_accepted],
                    ['label' => Yii::t('aff', 'Promo effectiveness'), 'url' => '/aff/hit/ad-effect', 'active' => Url::isActive('/aff/hit/ad-effect'), 'visible' => Account::current()->terms_accepted],
                    ['label' => Yii::t('aff', 'Attracted users'), 'url' => '/aff/attracted-users', 'active' => Url::isActive('/aff/attracted-users')],
                ],
            ]); ?>
        </div>
        <div class="col-xs-10">
            <?= $content ?>
        </div>
    </div>
<?php $this->endContent();