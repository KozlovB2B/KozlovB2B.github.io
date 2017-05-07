<?php

namespace app\modules\integration\modules\onlinepbx\components;

use yii\web\AssetBundle;

class LogoAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/modules/integration/modules/onlinepbx/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
}