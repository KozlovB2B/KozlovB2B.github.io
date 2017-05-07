<?php
use app\modules\script\components\CallAssetBundle;
use app\modules\script\models\ar\Script;
use yii\helpers\Url;

/**
 * @var app\modules\script\models\Script $script
 * @var string $action
 * @var app\modules\script\models\Call $model
 * @var $this yii\web\View
 * @var $key string
 */


/** @var app\modules\integration\modules\amo\controllers\ScriptController $controller */
$controller = Yii::$app->controller;

CallAssetBundle::register($this);


Yii::$app->getModule('site');
Yii::$app->getModule('script');

$this->registerJs("window['callPerformInstance'] = new Call(" . json_encode($script->callData()) . ", false, '$key');");
$this->registerJs("new ParentFrameMessenger();");

//echo $this->render('perform/_recorder');

echo $this->render('perform/_form', ['model' => $model, 'action' => $action]);

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