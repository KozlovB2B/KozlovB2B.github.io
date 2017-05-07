<?php
namespace app\modules\core\components;

use yii\web\AssetBundle;

/**
 * Bower asset for Bootstrap Tree View
 */
class BootstrapDatePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-datepicker/dist';

    public $js = [
        'js/bootstrap-datepicker.min.js',
        'locales/bootstrap-datepicker.ru.min.js',
    ];

    public $css = [
        'css/bootstrap-datepicker3.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}