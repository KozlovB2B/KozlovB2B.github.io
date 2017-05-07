<?php
namespace app\modules\script\components\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * Class ScriptEditorAsset
 */
class ContextAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/script/assets/context';

    public $js = [
        'field.js',
        'context.js',
    ];

    public $depends = [
        'app\modules\site\components\assets\V2DesignAsset'
    ];
}