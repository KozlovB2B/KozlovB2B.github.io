<?php
namespace app\modules\script\components;

use yii\web\AssetBundle as BaseAssetBundle;

class ScreenAssetBundle extends BaseAssetBundle
{
    public static $v = 1;

    public $sourcePath = '@app/modules/script/assets';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $js = [
        'js/jsPlumb-1.7.10-min.js',
        'js/toolkit.js',
        'js/render_script.js?v=11'
    ];

    public $css = [
        'css/jsPlumbToolkit-defaults.css',
        'css/demo.css',
        'css/app.css?v=9',
    ];
}