<?php

namespace app\modules\script\components\assets;

use yii\web\AssetBundle;

/**
 * Class V2DesignAsset
 */
class PanZoomAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/script/assets/panzoom-fixed';

//    public $sourcePath = '@bower/jquery.panzoom/dist';

    public $js = [
        'jquery.panzoom.js'
    ];

    public $depends = [
        '\yii\web\JqueryAsset'
    ];
}