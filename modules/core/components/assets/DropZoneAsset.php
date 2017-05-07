<?php

namespace app\modules\core\components\assets;

use yii\web\AssetBundle;

/**
 * Class DropZoneAsset
 * @package app\modules\user\components
 */
class DropZoneAsset extends AssetBundle
{
    public $sourcePath = '@bower/dropzone/dist/min';
    public $css = [
        'basic.min.css',
        'dropzone.min.css',
    ];
    public $js = [
        'dropzone.min.js',
    ];
}