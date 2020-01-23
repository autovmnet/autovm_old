<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'autovm',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'session', 'lang'],
    'language' => 'en',
    'timeZone' => 'GMT',
    'defaultRoute' => 'site/default',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'site' => 'app\modules\site\Site',
        'admin' => 'app\modules\admin\Admin',
		'api' => 'app\modules\api\Api',
        'candy' => 'app\modules\candy\Candy',
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components' => [
		'lang' => 'app\components\Lang',
    	'assetManager' => [
    		'linkAssets' => true,
    	],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mMSEH2PKbKevInuOsNvXh9_oPtNSC2et',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'error/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
            //'transport' => [
            //    'class' => 'Swift_SmtpTransport',
            //    'host' => 'smtp.gmail.com',
            //    'username' => '',
            //    'password' => '',
            //    'port' => '465',
            //    'encryption' => 'ssl',
            //],
            'messageConfig' => [
                'from' => 'noreply@autovm.net',
            ],
        ],
        'helper' => [
            'class' => 'app\components\Helper',
        ],
        'setting' => [
            'class' => 'app\components\Setting',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
		    'showScriptName' => false,
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
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
