<?php

// comment out the following two lines when deployed to production

date_default_timezone_set('Africa/Kampala');
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
define ('SITE_ROOT', realpath(dirname(__FILE__)));

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';

(new yii\web\Application($config))->run();
