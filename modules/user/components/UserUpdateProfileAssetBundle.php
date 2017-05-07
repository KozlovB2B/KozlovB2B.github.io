<?php
namespace app\modules\user\components;


use app\modules\core\components\BaseAssetBundle;

/**
 * Class OperatorRegistrationFormAssetBundle
 * @package app\modules\user\components
 */
class UserUpdateProfileAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/user/assets/user-update-profile';

    public $js = [
        'user-update-profile.js',
    ];
}