<?php

return [
    'id' => 'ScriptDesigner',
    'name' => 'ScriptDesigner.ru',
    'timeZone' => 'Europe/Moscow',
    // set target language to be Russian
    'language' => 'ru-RU',
    // set source language to be English
    'sourceLanguage' => 'en-US',

    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layoutPath' => dirname(__DIR__) . "/modules/site/views/layouts",
    'defaultRoute' => 'site/site/index',
    'modules' => [
        'blog' => 'app\modules\blog\Module',
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => '@webroot/uploads/posts',
            'uploadUrl' => '@web/uploads/posts',
            'imageAllowExtensions' => ['jpg', 'png', 'gif']
        ],
        'integration' => [
            'class' => 'app\modules\integration\Module',
        ],
        'sales' => [
            'class' => 'app\modules\sales\Module',
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
            'modules' => [
                'v1' => 'app\modules\api\v1\Module',
                'v2' => 'app\modules\api\v2\Module',
            ]
        ],
        'script' => [
            'class' => 'app\modules\script\Module',
        ],
        'site' => [
            'class' => 'app\modules\site\Module',
        ],
        'core' => [
            'class' => 'app\modules\core\Module',
        ],
        'billing' => [
            'class' => 'app\modules\billing\Module',
        ],
        'aff' => [
            'class' => 'app\modules\aff\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
        ]
    ],
    'aliases' => [
        '@default-script' => '/static/default-script',
        '@call-records' => '/call-records',
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'kJ7Pau74SytpicFXSXATYvx1dmN-aO-l',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'enableAutoLogin' => false,
            'enableSession' => true,
            'authTimeout' => 1209600,
            'identityClass' => 'app\modules\user\models\User',
            'on afterLogin' => ['app\modules\user\models\UserAuthLog', 'write'],
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'writeCallback' => function () {
                return [
                    'user_id' => Yii::$app->getUser()->getId(),
                    'ip' => Yii::$app->getRequest()->getUserIP()
                ];
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => YII_ENV_DEV,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                "/fields" => "/script/field/index",
                "/conversion" => "/script/script/conversion",
                "/profile" => "/site/users/profile",
                "/blog" => "/blog/post/blog",
                "/logout" => "/user/user/logout",
                "/blog/<id:\d+>" => "/blog/post/view",
                "/blog/<id:[a-z0-9-]++>" => "/blog/post/view",
                "/login" => "/user/user/login",
                "/calls" => "/script/call/index",
                "/operators" => "/site/user-operator/children",
                "/welcome" => "/site/site/welcome",
                "/scripts" => "/script/script/index",
                "/dashboard" => "/site/site/head-dashboard",
                "/designer-dashboard" => "/site/site/designer-dashboard",
                "/manual" => "/site/site/manual",
                "/operator-dashboard" => "/site/site/operator-dashboard",
                "/script/create" => "/script/script/create",
                "/billing" => "/billing/account/manage",
                "/aff" => "/aff/account/manage",
                "/aff/terms" => "/aff/account/terms",
                "/attracted-users" => "/aff/account/attracted-users",
                "/aff/attracted-users" => "/aff/account/attracted-users",
                "/instructions" => "/site/instruction/index",
                "/offer" => "/site/site/offer",
                "/faq" => "/site/site/faq",
                "/support" => "/site/site/support",
                "/contact" => "/site/site/contact",
                "/instruction/<id:\d+>" => "/site/instruction/view",
                "/guard" => "/site/multi-session-guard/ask",
                "/ask-terminate-other-sessions" => "/site/multi-session-guard/ask-terminate-other-sessions",
                "/ask-terminate-other-sessions-api" => "/site/multi-session-guard/ask-terminate-other-sessions-api",
                "/rates" => "/billing/rate/list",
                "/aff/promo-links" => "/aff/promo-link/index",
                "/aff/hits" => "/aff/hit/index",
                "/aff/hit" => "/aff/hit/index",
                "/integration" => "/integration/integration/index",
                "/sip-accounts" => "/script/sip-account/index",
                "/sip-account" => "/script/sip-account/update",
                "/ws" => "/site/site/ws"
            ],
        ],
        'db' => require(__DIR__ . '/generated/db.php')
    ],
    'params' => require(__DIR__ . '/params.php'),
];
