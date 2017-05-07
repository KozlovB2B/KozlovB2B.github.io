<?php
namespace app\modules\user\components;


use app\modules\core\components\BaseAssetBundle;

/**
 * Class OperatorRegistrationFormAssetBundle
 * @package app\modules\user\components
 */
class OperatorRegistrationFormAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/user/assets/operator-registration-form';

    public $js = [
        'js/operator-registration-form.js',
    ];
}