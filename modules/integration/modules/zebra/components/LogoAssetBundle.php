<?php

namespace app\modules\integration\modules\zebra\components;

use yii\web\AssetBundle;

class LogoAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/modules/integration/modules/zebra/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
}