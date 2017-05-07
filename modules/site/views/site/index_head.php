<?php
use app\modules\site\components\InstructionAssetBundle;
use app\modules\site\models\EmailServices;
use yii\helpers\Html;
use app\modules\user\models\profile\Head;
use app\modules\core\components\widgets\GlyphIcon;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var app\modules\user\models\User $user */
/* @var yii\data\ActiveDataProvider $scripts_data_provider */

InstructionAssetBundle::register($this);
$this->title = Yii::t('site', 'Hello') . ', ' . Head::current()->first_name . '!';
?>
<div id="site___head_dashboard__widget_usability_advise" class="alert alert-success visible-xs small hide">
    Чтобы использовать все возможности панели администратора - увеличьте размер виджета или разверните его на весь экран.
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
            <?= $this->render('@app/modules/script/views/script/_dashboard_list', ['scripts_data_provider' => $scripts_data_provider]) ?>
        </div>
        <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
            <?= $this->render('@app/modules/user/views/head/_team_invite_buttons') ?>
            <?= $this->render('@app/modules/user/views/operator/_head_dashboard_list') ?>
            <?= $this->render('@app/modules/user/views/designer/_head_dashboard_list') ?>
        </div>
    </div>
</div>