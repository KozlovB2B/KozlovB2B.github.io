<?php
namespace app\modules\core\components;

use yii\web\AssetBundle;

class CoreAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/modules/core/assets';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $js = [
        'js/core.js?v=3',
        'js/moment.min.js?v=3',
        'js/bootstrap-timepicker.min.js?v=3',
    ];
    public $css = [
        'css/core.css?v=1',
        'css/bootstrap-timepicker.min.css',
    ];
}