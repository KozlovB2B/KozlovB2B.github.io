<?php
namespace app\modules\blog\components;

use yii\web\AssetBundle as BaseAssetBundle;

class AssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/blog/assets';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $js = [
        'js/aff.js'
    ];
    public $css = [
        'css/aff.css'
    ];
}