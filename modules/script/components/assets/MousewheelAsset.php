<?php

namespace app\modules\script\components\assets;

use yii\web\AssetBundle;

/**
 * Class V2DesignAsset
 */
class MousewheelAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-mousewheel';

    public $js = [
        'jquery.mousewheel.min.js'
    ];

    public $depends = [
        '\yii\web\JqueryAsset'
    ];
}