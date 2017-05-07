<?php

namespace app\modules\core\components\assets;

use yii\web\AssetBundle as BaseAssetBundle;

class YiijAsset extends BaseAssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/core/assets/yiij';
    
    public $js = [
        'Yiij.js',
        'base/Object.js',
        'base/Event.js',
        'base/Component.js',
        'base/Locator.js',
        'base/Module.js',
        'base/Application.js',
        'base/Model.js',
        'base/Controller.js',
        'web/Application.js',
    ];
}