<?php

namespace app\modules\integration\modules\hookz\components;

use yii\web\AssetBundle;

class LogoAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/modules/integration/modules/hookz/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
}