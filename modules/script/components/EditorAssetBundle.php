<?php
namespace app\modules\script\components;

use yii\web\AssetBundle as BaseAssetBundle;

class EditorAssetBundle extends BaseAssetBundle
{
    public static $v = 1;

    public $sourcePath = '@app/modules/script/assets/editor';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $js = [
//        'js/jQuery-2.0.3.js',
//        'js/Lodash-3.10.1.js',
//        'js/Backbone-1.2.1.js',
//        'js/joint.min.js',
//        'js/rappid.js',
//        'js/editor.js',
//        'js/editor-rapid.js',

'js/jquery.js',
'js/lodash.js',
'js/backbone.js',
'js/graphlib.core.js',
'js/dagre.core.js',
'js/keyboard.js',
'js/rappid.min.js',
//'js/inspector.js',
'js/shapes.js',
'js/main.js',
    ];

    public $css = [
//        'css/joint.min.css',
        'css/editor.css',
        
        'css/rappid.min.css',
//        'css/layout.css',
//        'css/paper.css',
//        'css/inspector.css',
//        'css/navigator.css',
//        'css/stencil.css',
//        'css/halo.css',
//        'css/selection.css',
//        'css/toolbar.css',
//        'css/statusbar.css',
        'css/freetransform.css',
//        'css/style.css',
    ];
}