<?php

namespace app\modules\integration\modules\amo\components;

use yii\web\AssetBundle;

class LogoAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/modules/integration/modules/amo/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
}