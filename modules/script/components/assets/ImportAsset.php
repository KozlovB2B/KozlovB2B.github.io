<?php

namespace app\modules\script\components\assets;

use yii\web\AssetBundle;

/**
 * Class ChangeTrainingImageAssetBundle
 * @package app\modules\script\components\assets
 */
class ImportAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/script/assets/import-form';

    public $js = [
        'import-form.js',
    ];

    public $depends = [
        'app\modules\core\components\assets\DropZoneAsset'
    ];
}