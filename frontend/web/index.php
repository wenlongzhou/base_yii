<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

define('ROOT_PATH', __DIR__ . '/../../');

require ROOT_PATH . '/vendor/autoload.php';
require ROOT_PATH . '/vendor/yiisoft/yii2/Yii.php';
require '../config/bootstrap.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
