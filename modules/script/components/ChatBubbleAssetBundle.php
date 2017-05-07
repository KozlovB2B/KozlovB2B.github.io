<?php
namespace app\modules\script\components;

use yii\web\AssetBundle as BaseAssetBundle;

class ChatBubbleAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/script/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $css = [
        'css/chat-bubble.css?v=13',
    ];
}