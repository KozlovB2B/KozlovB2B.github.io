<?php

namespace app\modules\core\components\assets;

use yii\web\AssetBundle as BaseAssetBundle;

class WysiAsset extends BaseAssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/core/assets/wysi';
    
    public $css = [
        'wysi.css',
        'wysi-panel.css',
    ];

    public $js = [
        'wysihtml5_parcer_rules.js',
        'wysihtml5-0.3.0.js',
    ];
}