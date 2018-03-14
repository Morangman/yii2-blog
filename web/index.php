<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    // ensure code works on php < 7.0.0 to not break BC
    class_alias('yii\base\BaseObject', 'yii\base\Object', false);
}

(new yii\web\Application($config))->run();
