<?php
namespace app\modules\user\components;

use app\modules\core\components\BaseAssetBundle;

/**
 * Class OperatorLayoutAsset
 * @package app\modules\user\components
 */
class OperatorLayoutAsset extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/user/assets/operator-layout';

    public $forceCopy = true;

    public $css = [
        'css/tse.css',
        'css/style.css',
    ];

    public $js = [
        'js/tse.js',
        'js/scripts.js',
        'js/holder.min.js'
    ];
}