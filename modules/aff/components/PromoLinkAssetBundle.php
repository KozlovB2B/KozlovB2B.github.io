<?php
namespace app\modules\aff\components;

use yii\web\AssetBundle as BaseAssetBundle;

class PromoLinkAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/aff/assets';
    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];
    public $js = [
        'js/promo-link.js'
    ];
}