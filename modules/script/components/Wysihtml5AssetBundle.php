<?php
namespace app\modules\script\components;

use yii\web\AssetBundle as BaseAssetBundle;

class Wysihtml5AssetBundle extends BaseAssetBundle
{
    public static $v = 1;

    public $sourcePath = '@app/modules/script/assets';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $js = [
        'js/wysihtml5-0.3.0.js',
        'js/wysihtml5_parcer_rules.js'
    ];
}