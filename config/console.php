<?php
return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Europe/Moscow',
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'core' => [
            'class' => 'app\modules\core\Module',
            'controllerNamespace' => 'app\modules\core\commands'
        ],
        'sales' => [
            'class' => 'app\modules\sales\Module',
            'controllerNamespace' => 'app\modules\sales\commands'
        ],
        'billing' => [
            'class' => 'app\modules\billing\Module',
        ],
        'aff' => [
            'class' => 'app\modules\aff\Module',
            'controllerNamespace' => 'app\modules\aff\commands'
        ],
        'rbacc' => [
            'class' => 'rbacc\Module',
            'collection' => [
                'app\modules\user\rbac\Config',
                'app\modules\site\rbac\Config',
                'app\modules\script\rbac\Config',
                'app\modules\billing\rbac\Config',
                'app\modules\aff\rbac\Config',
                'app\modules\sales\rbac\Config',
                'app\modules\blog\rbac\Config',
                'app\modules\integration\rbac\Config',
            ]
        ],
    ],
    'controllerMap' => [
        'billing' => 'app\modules\billing\controllers\ProcedureController',
        'rbac' => 'app\modules\site\controllers\RbacController',
        'user' => 'app\modules\user\commands\UserController',
        'script' => 'app\modules\script\controllers\ScriptConsoleController'
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/generated/db.php'),
    ],
    'params' => require(__DIR__ . '/params.php'),
];