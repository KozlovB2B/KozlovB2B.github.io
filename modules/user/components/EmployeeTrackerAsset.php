<?php
namespace app\modules\user\components;

use yii\web\AssetBundle;

/**
 * Class OperatorLayoutAsset
 *
 * @package app\modules\user\components
 */
class EmployeeTrackerAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/user/assets/employee-tracker';

    public $js = [
        'employee-tracker.js',
    ];

    public $depends = [
        'app\modules\core\components\assets\YiijAsset',
    ];
}