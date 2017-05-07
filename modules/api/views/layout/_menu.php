<?php
use yii\helpers\Url;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;

?>
<style>
    .widget_menu {
        background: #f9f9f9;
    }

    .widget_menu a {
        color: #6d6d6d;
        padding-top: 4px;
        padding-bottom: 4px;
        opacity: .9;
        display: inline-block;
    }

    .widget_menu a:hover {
        opacity: 1;
        text-decoration: none;
    }
</style>
<div class="container-fluid">
    <div class="row widget_menu">
        <div class="col-xs-3">
            <?php echo Html::a(Yii::t("script", 'Scripts'), Url::to(['/api/v1/script/index'])) ?>
        </div>
        <div class="col-xs-9">
            <div class="pull-right">
                <?php echo Html::a(Yii::t("user", 'Logout') . ' (' . Yii::$app->user->identity->username . ')', Url::to(['/api/v1/auth/logout']), ['data-method' => 'post']) ?>
            </div>
        </div>
    </div>
</div>
