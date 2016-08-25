<?php
// Bolt Dependecies
include_once __DIR__ . '/../vendor/bolt/bolt/tests/phpunit/bootstrap.php';

// Extension Dependecis
include_once __DIR__ . '/../thirdparty/vendor/autoload.php';

define('EXTENSION_AUTOLOAD',  realpath(dirname(__DIR__) . '/vendor/autoload.php'));

define('BOOKME_EXTENSION_PATH',  realpath('/../'.dirname(__DIR__)));


require_once EXTENSION_AUTOLOAD;


