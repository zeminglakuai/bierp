<?php

$mem_before = memory_get_usage();
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

error_reporting(0);
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
$mem_after = memory_get_usage();

// $unit=array('b','kb','mb','gb','tb','pb');
// @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];

// echo 'asdasdasdas'.convert(memory_get_usage(true));

// function convert($size)
// {
//     $unit=array('b','kb','mb','gb','tb','pb');
//     return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
// }
