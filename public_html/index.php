<?php
// todo удалить phantomjs после полного отказа от v1
// If we have affiliate code - first save hit
if (isset($_GET['p'])) {
    require_once '../modules/aff/components/hits-engine/engine.php';
}

ini_set('session.gc_maxlifetime', 1209600);


if (file_exists(__DIR__ . '/../config/generated/web.dev.php')) {
    $config_filename = __DIR__ . '/../config/generated/web.dev.php';
    defined('YII_ENV') || define('YII_ENV', 'dev');
    defined('YII_DEBUG') or define('YII_DEBUG', true);
} else {
    if (file_exists(__DIR__ . '/../config/division/en.php')) {
        $config_filename = __DIR__ . '/../config/division/en.php';
    }else{
        $config_filename = __DIR__ . '/../config/web.php';
    }

    defined('YII_ENV') || define('YII_ENV', 'prod');
    defined('YII_DEBUG') or define('YII_DEBUG', false);
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

//if (YII_ENV === "dev") {
//    $config = require(__DIR__ . '/../config/web_en.php');
//    $config = require(__DIR__ . '/../config/web_ru.php');
//} else {
//    if ($_SERVER['HTTP_HOST'] == 'www.salesscriptprompter.com') {
//        $config = require(__DIR__ . '/../config/web_en.php');
//    } else {
//        $config = require(__DIR__ . '/../config/web_ru.php');
//    }
//}

//configure Pinba
if (function_exists('pinba_script_name_set')) {
    pinba_script_name_set($_SERVER['REQUEST_URI']);
}

(new yii\web\Application(require($config_filename)))->run();