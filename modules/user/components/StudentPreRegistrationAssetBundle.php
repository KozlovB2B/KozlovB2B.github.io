<?php
namespace app\modules\user\components;


use app\modules\core\components\BaseAssetBundle;

/**
 * Class OperatorRegistrationFormAssetBundle
 * @package app\modules\user\components
 */
class OperatorPreRegistrationAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/user/assets/pre-registration';

    public $css = [
        'style.css',
    ];
}