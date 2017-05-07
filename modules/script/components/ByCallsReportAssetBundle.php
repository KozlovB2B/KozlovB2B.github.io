<?php
namespace app\modules\script\components;

use yii\web\AssetBundle as BaseAssetBundle;

class ByCallsReportAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/script/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $js = [
        'js/by-calls-report.js'
    ];
}