<?php
namespace app\modules\site\components;

class PublicAssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/site/assets/public';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $css = [
        'css/main.css'
    ];

//    public $js = [
//        'js/common.js?v=1'
//    ];
//
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}