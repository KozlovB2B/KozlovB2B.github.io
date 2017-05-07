<?php
namespace app\modules\site\components;

class InstructionAssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/site/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $css = [
        '/wiki/css/instruction.css'
    ];
    public $js = [
        '/wiki/js/instruction.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}