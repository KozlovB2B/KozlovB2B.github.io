<?php

namespace app\modules\script\components\assets;

use yii\web\AssetBundle;

/**
 * Class GiftAsset
 * @package app\modules\script\components\assets
 */
class GiftAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/script/assets/gift';

    public $css = [
        'gift.css'
    ];

    public $js = [
        'gift.js',
    ];

    public $depends = [
        '\app\modules\core\components\CoreAssetBundle',
        '\yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}