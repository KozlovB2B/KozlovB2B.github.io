<?php

namespace app\modules\core\components\assets;

use yii\web\AssetBundle as BaseAssetBundle;

class QTipAsset extends BaseAssetBundle
{
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js'
    ];

    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css'
    ];
}