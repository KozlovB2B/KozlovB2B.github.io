<?php
namespace app\modules\script\components;

use yii\web\AssetBundle as BaseAssetBundle;

class CallAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/script/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $js = [
        'js/call_perform.js?v=15'

    ];

    public $css = [
        'css/call-perform.css?v=13',
        'css/wysi.css?v=13',
    ];
}