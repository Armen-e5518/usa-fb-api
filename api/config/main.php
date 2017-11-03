<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php')
//    require(__DIR__ . '/params.php'),
//    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'en-EN',
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'api',
    'modules' => [
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'api\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
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
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/facebook',   // our country api rule,
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        '{page_id}' => '<page_id:\\d+>',
                        '{live}' => '<live:\\d+>',
                    ],
                    'extraPatterns' => [
                        'POST get-page-id-by-url' => 'get-page-id-by-url',
                        'GET  get-page-data-by-page-id/{page_id}/{live}' => 'get-page-data-by-page-id',
                    ],
                ],
                '<action:[\w\-]+>' => 'api/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '' => ''
            ],
        ],
        'request' => [
            'baseUrl' => '/api',
            'enableCookieValidation' => false,
            'parsers' => [
                'multipart/form-data' => 'yii\web\MultipartFormDataParser'
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
            'format' => \yii\web\Response::FORMAT_JSON,
        ]

    ],
    'params' => $params,
];