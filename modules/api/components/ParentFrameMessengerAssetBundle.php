<?php
namespace app\modules\api\components;

use yii\web\AssetBundle as BaseAssetBundle;

class ParentFrameMessengerAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/api/assets/parent-frame-messenger';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $js = [
        'parent_frame_messenger.js'
    ];
}