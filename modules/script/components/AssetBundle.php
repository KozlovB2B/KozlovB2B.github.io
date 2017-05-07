<?php
namespace app\modules\script\components;

use yii\web\AssetBundle as BaseAssetBundle;

class AssetBundle extends BaseAssetBundle
{
    public static $v = 1;

    public $sourcePath = '@app/modules/script/assets';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $js = [
//        'js/selectize-plugin-clear.js',
//        'js/simulate/libs/jquery.simulate.js',
//        'js/simulate/src/jquery.simulate.ext.js',
//        'js/simulate/src/jquery.simulate.drag-n-drop.js',
//        'js/simulate/src/jquery.simulate.key-combo.js',
//        'js/simulate/src/jquery.simulate.key-sequence.js',
//        'js/jsPlumb-1.7.10-min.js',
//        'js/jquery-ui.min.js',
//        'js/interact.js',
//        'js/toolkit.js',
//        'js/script_designer.js?v=12',
//        'js/call2.js?v=12',
        'js/script.js?v=12'
    ];

    public $css = [
//        'css/jsPlumbToolkit-defaults.css',
//        'css/demo.css?v=12',
//        'css/app.css?v=12',
    ];
}