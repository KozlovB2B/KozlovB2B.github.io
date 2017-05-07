<?php

namespace app\modules\core\components\assets;

use yii\web\AssetBundle as BaseAssetBundle;

class JsToolsAsset extends BaseAssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/core/assets/jstools';
    
    public $js = [
        'base64.js',
        'timestamp.js',
        'uuid.js',
    ];
}