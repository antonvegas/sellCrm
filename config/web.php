<?php

$params = require(__DIR__ . '/params.php');


$config = [
    'timeZone' => 'Europe/Moscow',
    'id' => 'basic',
    'language' => 'ru',

    'as AccessBehavior' => [
        'class' => 'app\modules\Settings\behaviors\AccessBehavior', //AccessBehavior::className(),
        'rules' =>
            [
                'site' =>
                [
                    [
                        'actions' => ['login', 'index', 'logout', 'forbidden'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['about'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],

                'gii' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['admin'],
                    ]
                ],

                'debug' =>
                    [
                        [
                            'actions' => [],
                            'allow' => true,
                        ],
                    ],


            ],
        'redirect_url' =>  '/site/forbidden', 
        'login_url' =>  '/login'
    ],

    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'IzQ8PIWNL2eo2lmXYjz2fS-tnCa4xjAl',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/b' => '/backend/sale/index',
                '/login' => '/site/login'
            ],
        ],

    ],
    'params' => $params,
    'modules' => [
        'settings' => [
            'class' => 'app\modules\Settings\module',
            'params' => [
                'userClass' => 'app\models\Users'
            ]
        ],
        'backend' => [
            'class' => 'app\modules\Backend\module'
        ],
    ],

];



if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment


    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*']
    ];

}

$config['bootstrap'][] = 'gii';
$config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'allowedIPs' => ['*']
];



return $config;
