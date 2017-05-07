<?php
namespace app\modules\site\components;

class UsersBundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/site/assets';

    public $publishOptions = [
        "forceCopy" => YII_DEBUG
    ];

    public $js = [
        'js/users.js',
    ];

    public $css = [];
}