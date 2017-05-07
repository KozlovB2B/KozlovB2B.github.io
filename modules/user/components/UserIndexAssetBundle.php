<?php
namespace app\modules\user\components;


use app\modules\core\components\BaseAssetBundle;

/**
 * Class OperatorRegistrationFormAssetBundle
 * @package app\modules\user\components
 */
class UserIndexAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/user/assets/user-index';

    public $js = [
        'script.js',
    ];
}