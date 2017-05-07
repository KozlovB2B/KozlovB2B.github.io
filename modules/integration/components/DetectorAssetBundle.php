<?php
namespace app\modules\integration\components;

use yii\web\AssetBundle as BaseAssetBundle;

class DetectorAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/integration/assets/detector';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $js = [
        'js/integration_detector.js'
    ];
}