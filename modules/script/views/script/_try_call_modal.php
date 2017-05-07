<?php
/** @var OperatorRegistrationForm $model */
/* @var app\modules\script\models\Script $script */

use app\modules\user\models\OperatorRegistrationForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;

Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Test call data is ready...')),
    'id' => 'script___script___try_call_modal',
    'size' => Modal::SIZE_SMALL,
]); ?>
    <br/>
    <div class="text-center">
        <a id="script___designer__try_script_link" class="btn btn-success" target="_blank">
            <?= Yii::t('script', 'Try a test call!') ?>
        </a>
    </div>
    <br/>
    <br/>
    <small class="text-center">
        <?= Yii::t('script', 'This modal will be closed automatically after 20 seconds...') ?>
    </small>
<?php
Modal::end();