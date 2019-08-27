<?php

use suframe\service\Core;

$loader = require (__DIR__ . '/../../../vendor/autoload.php');
defined('SUMMER_APP_ROOT') or define('SUMMER_APP_ROOT', __DIR__ . '/../');
$loader->addPsr4("app\\", SUMMER_APP_ROOT);

$loader2 = require (__DIR__ . '/../vendor/autoload.php');
$summer = Core::getInstance()->init();
