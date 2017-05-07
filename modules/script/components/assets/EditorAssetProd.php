<?php

namespace app\modules\script\components\assets;

use yii\web\AssetBundle;

/**
 * Class ScriptEditorAsset
 */
class EditorAssetProd extends AssetBundle
{
    public $sourcePath = '@app/modules/script/assets/editor-prod';

    public $css = [
        'mywhalalla.css'
    ];

    public $js = [
        'mywhalalla.js',
    ];

    public $depends = [
        'app\modules\site\components\assets\V2DesignAsset',
        'app\modules\core\components\assets\SelectizeAsset',
        'app\modules\script\components\assets\MousewheelAsset',
        'app\modules\script\components\assets\PanZoomAsset',
        'app\modules\site\components\assets\CentrifugeAsset',
        'app\modules\core\components\assets\JsToolsAsset',
        'app\modules\core\components\assets\QTipAsset',
        'app\modules\core\components\assets\WysiAsset',
    ];
}