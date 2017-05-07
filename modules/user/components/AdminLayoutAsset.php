<?php
namespace app\modules\user\components;

use yii\web\AssetBundle;

/**
 * Class OperatorLayoutAsset
 *
 * @package app\modules\user\components
 */
class AdminLayoutAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/user/assets/admin-layout';

    public $css = [
        'css/style.css',
    ];

    public $js = [
        'js/scripts.js'
    ];
}