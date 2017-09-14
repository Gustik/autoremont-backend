<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'automaster',
    'basePath' => dirname(__DIR__),
    'language' => 'ru',
    'bootstrap' => ['log'],
    'modules' => [
        'api' =>[
            'class' => 'yii\base\Module',
            'basePath' => '@app/modules/api',
            'controllerNamespace' => 'app\modules\api\controllers'
        ],
        'admin' =>[
            'class' => 'app\modules\admin\Module',
        ],
        'partner' =>[
            'class' => 'app\modules\partner\Module',
        ],
        'discount' =>[
            'class' => 'app\modules\discount\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'redactor' => 'yii\redactor\RedactorModule',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'fHpi9xZt6YEUuUDv3bj3bjt8OCYPYZS_',
            'parsers' => ['application/json' => 'yii\web\JsonParser']
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function($event) {
                $response = $event->sender;
                if ($response->data !== null && Yii::$app->request->get('suppress_response_code')) {
                    $response->statusCode = 200;
                }
            }
        ],
        'apns' => [
            'class' => 'bryglen\apnsgcm\Apns',
            'environment' => \bryglen\apnsgcm\Apns::ENVIRONMENT_SANDBOX,
            'pemFile' => dirname(__FILE__).'/apnscert/server_certificates_bundle_sandbox.pem',
            // 'retryTimes' => 3,
            'options' => [
                'sendRetryTimes' => 5
            ]
        ],
        'gcm' => [
            'class' => 'bryglen\apnsgcm\Gcm',
            'apiKey' => 'AIzaSyBR2bIRlaaSyHwDh-UmQn0-uSDbOh1mxo0',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
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
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
                'license' => 'site/page',
                'privacy' => 'site/page/2',
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';

    $config['components']['assetManager']['forceCopy'] = true;
}

return $config;
