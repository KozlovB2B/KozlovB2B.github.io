<?php
use app\modules\site\components\InstructionAssetBundle;
use app\modules\user\models\profile\Designer;


/* @var $this yii\web\View */
/* @var app\modules\user\models\User $user */
/* @var yii\data\ActiveDataProvider $scripts_data_provider */

InstructionAssetBundle::register($this);
$this->title = Yii::t('site', 'Hello') . ', ' . Designer::current()->first_name . '!';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-md-12 col-xs-12">
            <?= $this->render('@app/modules/script/views/script/_dashboard_list', ['scripts_data_provider' => $scripts_data_provider]) ?>
        </div>
    </div>
</div>