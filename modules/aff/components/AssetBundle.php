<?php
namespace app\modules\aff\components;

use yii\web\AssetBundle as BaseAssetBundle;

class AssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/aff/assets';
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