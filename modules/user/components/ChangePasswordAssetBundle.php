<?php
namespace app\modules\user\components;


use app\modules\core\components\BaseAssetBundle;

/**
 * Class OperatorRegistrationFormAssetBundle
 * @package app\modules\user\components
 */
class ChangePasswordAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/user/assets/change-password';

    public $js = [
        'script.js',
    ];
}