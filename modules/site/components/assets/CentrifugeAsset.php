<?php

namespace app\modules\site\components\assets;

use yii\web\AssetBundle;

/**
 * Class V2DesignAsset
 */
class CentrifugeAsset extends AssetBundle
{
    public $sourcePath = '@bower/centrifuge';

    public $js = [
        '//cdn.jsdelivr.net/sockjs/1.1/sockjs.min.js',
        'centrifuge.min.js',
    ];
}