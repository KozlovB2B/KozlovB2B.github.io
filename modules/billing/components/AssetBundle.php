<?php
namespace app\modules\billing\components;

use yii\web\AssetBundle as BaseAssetBundle;

class AssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/billing/assets';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $js = [
        'js/billing.js?v=3'
    ];
    public $css = [
        'css/billing.css'
    ];
}