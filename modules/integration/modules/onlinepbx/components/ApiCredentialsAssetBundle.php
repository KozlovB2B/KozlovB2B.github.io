<?php

namespace app\modules\integration\modules\onlinepbx\components;

class ApiCredentialsAssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/integration/modules/onlinepbx/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $js = [
        'js/api-credentials.js'
    ];
}