<?php
/**
 * @var app\modules\script\models\Script $script
 * @var app\modules\script\models\Call $model
 * @var app\modules\script\models\SipAccount $sip
 * @var $this yii\web\View
 */

use app\modules\script\components\CallAssetBundle;
use app\modules\script\models\ar\Script;

$this->title = Yii::t('script', 'Call by script') . ' #' . $script->id . ' ' . $script->name;

$this->params['breadcrumbs'][] = $this->title;

CallAssetBundle::register($this);

$this->registerJs("window['callPerformInstance'] = new Call(" . json_encode($script->callData()) . ");");

//echo $this->render('perform/_recorder');

if (isset($sip) && $sip->public_identity) {
    echo $this->render('perform/_sip_call', ['sip' => $sip]);
}


echo $this->render('perform/_form', ['model' => $model]);

echo $this->render('perform/_start_screen');


switch ($script->operator_interface_type_id) {
    case Script::SCRIPT_OPERATOR_INTERFACE_TYPE_BUTTONS_LEFT:
        echo $this->render('perform/_working_area_buttons_left', ['model' => $model]);
        break;
    case Script::SCRIPT_OPERATOR_INTERFACE_TYPE_DEFAULT:
        echo $this->render('perform/_working_area_buttons_bottom', ['model' => $model]);
        break;
    case Script::SCRIPT_OPERATOR_INTERFACE_TYPE_LINKS_RIGHT:
    case Script::SCRIPT_OPERATOR_INTERFACE_TYPE_BUTTONS_RIGHT:
        echo $this->render('perform/_working_area_buttons_right', ['model' => $model]);
        break;
    default:
        echo $this->render('perform/_working_area_buttons_bottom', ['model' => $model]);
        break;
}