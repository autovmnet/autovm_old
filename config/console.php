<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'autovm-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'timeZone' => 'GMT',
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'setting' => [
            'class' => 'app\components\Setting',
        ],
		'helper' => [
			'class' => 'app\components\Helper',
		],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];
