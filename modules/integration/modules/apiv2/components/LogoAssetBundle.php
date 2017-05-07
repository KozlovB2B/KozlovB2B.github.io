<?php

namespace app\modules\integration\modules\apiv2\components;

use yii\web\AssetBundle;

class LogoAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/modules/integration/modules/apiv2/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
}