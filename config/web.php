<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute'=>'default',
	'timeZone'=>'Asia/Chongqing',
    'components' => [
        //'session' => [
        //        'class' => 'system.web.CDbHttpSession',
        //        'connectionID' => 'db'
        //],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'asdasdasdqwrvasdvasdfasdfsadfasdfasd',
        ],
        'assetManager' => [
                        'bundles' => [
                            'yii\web\JqueryAsset' => [
                                'js' =>[]
                            ],
                        ] ,
        ] ,
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'modules' => [
                'teamwork'=>['class'=>'app\teamwork\Teamwork'],
                ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
