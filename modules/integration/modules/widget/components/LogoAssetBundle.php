<?php

namespace app\modules\integration\modules\widget\components;

use yii\web\AssetBundle;

class LogoAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/modules/integration/modules/widget/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
}