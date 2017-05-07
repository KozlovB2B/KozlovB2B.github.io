<?php
use app\modules\site\components\InstructionAssetBundle;
use app\modules\site\models\EmailServices;
use yii\helpers\Html;
use app\modules\user\models\profile\Head;


/* @var $this yii\web\View */
/* @var app\modules\user\models\User $user */
/* @var yii\data\ActiveDataProvider $scripts_data_provider */

InstructionAssetBundle::register($this);
$this->title = Yii::t('site', 'Hello') . ', ' . Head::current()->first_name . '!';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <?php echo $this->render('_manual') ?>
        </div>
    </div>
</div>