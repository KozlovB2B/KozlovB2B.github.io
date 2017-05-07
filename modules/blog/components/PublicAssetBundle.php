<?php
namespace app\modules\blog\components;

use yii\web\AssetBundle as BaseAssetBundle;

class PublicAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/blog/assets/public';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $css = [
        'css/style.css'
    ];
}