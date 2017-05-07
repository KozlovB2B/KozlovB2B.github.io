<?php
namespace app\modules\site\components;

class LandingAssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/site/assets/landing';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $css = [
        'css/main.css?v=1',
        'css/adaptive.css?v=2',
        'https://fonts.googleapis.com/css?family=Open+Sans:400,700,400italic,300italic,600&subset=latin,cyrillic'
    ];

    public $js = [
        'js/common.js?v=1'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}