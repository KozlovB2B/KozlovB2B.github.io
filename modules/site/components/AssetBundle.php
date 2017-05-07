<?php
namespace app\modules\site\components;

class AssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/site/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $js = [
        'js/user-operator.js?v=1',
        'js/instruction.js?v=1'
    ];

    public $css = [];
}