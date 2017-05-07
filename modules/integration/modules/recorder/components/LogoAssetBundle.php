<?php

namespace app\modules\integration\modules\recorder\components;

use yii\web\AssetBundle;

class LogoAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/modules/integration/modules/recorder/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
}