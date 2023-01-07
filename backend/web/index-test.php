<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

$appcode = '/usr/share/php/bottradinggroup-yii2/';

require $appcode . 'vendor/autoload.php';
require $appcode . 'vendor/yiisoft/yii2/Yii.php';
require $appcode . 'common/config/bootstrap.php';
require $appcode . 'backend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require $appcode . 'common/config/main.php',
    require $appcode . 'common/config/main-local.php',
    require $appcode . 'common/config/test.php',
    require $appcode . 'common/config/test-local.php',
    require $appcode . 'backend/config/main.php',
    require $appcode . 'backend/config/main-local.php',
    require $appcode . 'backend/config/test.php',
    require $appcode . 'backend/config/test-local.php'
);

(new yii\web\Application($config))->run();
