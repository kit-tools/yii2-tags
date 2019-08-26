<?php

require_once __DIR__ . '/bootstrap.php';

return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@tests/_app/migrations',
                '@kittools/migrations',
            ],
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2-tags-test',
            'username' => 'root',
            'password' => '123',
            'charset' => 'utf8',
        ],
        /*'urlManager' => [
            'showScriptName' => true,
        ],*/
        /*'user' => [
            'identityClass' => 'app\models\User',
        ],*/
        /*'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],*/
    ],
];