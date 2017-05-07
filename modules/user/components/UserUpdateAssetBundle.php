<?php
namespace app\modules\user\components;


use app\modules\core\components\BaseAssetBundle;

/**
 * Class UserUpdateAssetBundle
 * @package app\modules\user\components
 */
class UserUpdateAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/user/assets/user-update';

    public $js = [
        'user-update.js',
    ];
}