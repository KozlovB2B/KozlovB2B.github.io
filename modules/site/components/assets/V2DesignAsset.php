<?php

namespace app\modules\site\components\assets;

use yii\web\AssetBundle;

/**
 * Class V2DesignAsset
 */
class V2DesignAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/site/assets/v2-design';


    public $css = [
        'css/base.css',
        'css/bs-theme/bootstrap.css',
        'css/bs-theme/bs-override.css',
    ];

    public $js = [
        'js/main.js'
    ];

    public $depends = [
        '\yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\modules\core\components\assets\YiijAsset',
    ];
}