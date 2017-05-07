<?php

namespace app\modules\integration\modules\amo\components;

class ApiCredentialsAssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/integration/modules/amo/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $js = [
        'js/api-credentials.js'
    ];
}