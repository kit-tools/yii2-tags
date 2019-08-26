#!/usr/bin/env php
<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
//require_once __DIR__ . '/config/bootstrap.php';

$config = require_once __DIR__ . '/config/test.php';

(new yii\console\Application($config))->run();