<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$appcode = '/usr/share/php/bottradinggroup-yii2/';

require $appcode . 'vendor/autoload.php';
require $appcode . 'vendor/yiisoft/yii2/Yii.php';
require $appcode . 'common/config/bootstrap.php';
require $appcode . 'frontend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require $appcode . 'common/config/main.php',
    require $appcode . 'common/config/main-local.php',
    require $appcode . 'frontend/config/main.php',
    require $appcode . 'frontend/config/main-local.php'
);

(new yii\web\Application($config))->run();
