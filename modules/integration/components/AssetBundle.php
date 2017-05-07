<?php
namespace app\modules\integration\components;

use yii\web\AssetBundle as BaseAssetBundle;

class AssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/integration/assets';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $css = [
        'css/integration.css'
    ];
}