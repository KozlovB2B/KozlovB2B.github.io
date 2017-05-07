<?php
namespace app\modules\script\components\assets;

use yii\web\AssetBundle as BaseAssetBundle;

class VariantsReportAsset extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/script/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $js = [
        'js/variants-report.js'
    ];
}