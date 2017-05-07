<?php

namespace app\modules\script\components\assets;

use yii\web\AssetBundle;

/**
 * Class ScriptEditorAsset
 */
class PerformerAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/script/assets/performer';

    public $css = [
        'css/performer.css'
    ];

    public $js = [
        'js/components/wsreconnectable.js',
        'js/components/recorder.js',
        'js/components/timer.js',
        'js/controllers/workspace.js',
        'js/controllers/call.js',
        'js/models/workspace.js',
        'js/models/call.js',
        'js/views/workspace.js',
        'js/views/call.js',
        'js/performer.js'
    ];

    public function init()
    {
        parent::init();

        foreach ($this->js as $k => $v) {
            $this->js[$k] .= '?v=4';
        }

        foreach ($this->css as $k => $v) {
            $this->js[$k] .= '?v=4';
        }
    }

    public $depends = [
        'app\modules\site\components\assets\V2DesignAsset',
        'app\modules\site\components\assets\CentrifugeAsset',
        'app\modules\core\components\assets\QTipAsset',
        'app\modules\core\components\assets\JsToolsAsset',
        'app\modules\core\components\assets\WysiAsset',
        'app\modules\core\components\assets\YiijAsset',
    ];
}