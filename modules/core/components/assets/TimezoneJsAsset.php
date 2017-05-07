<?php

namespace app\modules\core\components\assets;

use yii\web\AssetBundle;

/**
 * Class TimezoneJsAsset
 *
 * Сборная солянка для работы с таймзонами в JS
 *
 * Чтобы обновить файлы из папки tz - используйте update-tz.sh
 *
 * @package app\modules\core\components
 */
class TimezoneJsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/core/assets/timezone-js';

    public $js = [
        'date.js', // https://github.com/mde/timezone-js
        'detect.js', // https://www.npmjs.com/package/jstz
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @inheritdoc
     */
    public static function register($view)
    {
        $bundle = parent::register($view);

        $view->registerJs("timezoneJS.timezone.zoneFileBasePath = '" . $bundle->baseUrl . "/tz'; timezoneJS.timezone.init({ callback: function(){} });");

        return $bundle;
    }
}