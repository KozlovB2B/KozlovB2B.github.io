<?php
namespace app\modules\script\components;

use yii\web\AssetBundle as BaseAssetBundle;

class RecorderAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/script/assets/';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $js = [
        'js/recorder/audiodisplay.js',
        'js/recorder/recorder.js',
        'js/recorder/record-call.js'
    ];
}