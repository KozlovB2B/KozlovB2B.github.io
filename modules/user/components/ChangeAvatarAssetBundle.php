<?php
namespace app\modules\user\components;


use app\modules\core\components\BaseAssetBundle;

/**
 * Class OperatorRegistrationFormAssetBundle
 * @package app\modules\user\components
 */
class ChangeAvatarAssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@app/modules/user/assets/change-avatar';

    public $js = [
        'change-avatar.js',
    ];

    public $depends = [
        'app\modules\core\components\DropZoneAsset'
    ];
}