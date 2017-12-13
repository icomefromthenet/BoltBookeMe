<?php
// Install base location
if (!defined('TEST_ROOT')) {
    define('TEST_ROOT', realpath(__DIR__. '/../vendor/bolt/bolt/'));
}


// PHPUnit's base location
if (!defined('PHPUNIT_ROOT')) {
    define('PHPUNIT_ROOT', realpath(TEST_ROOT . '/tests/phpunit/unit'));
}



// PHPUnit's temporary web root… It doesn't exist yet, so we can't realpath()
if (!defined('PHPUNIT_WEBROOT')) {
    define('PHPUNIT_WEBROOT', PHPUNIT_ROOT . '/web-root');
}


if (!defined('NUT_PATH')) {
    define('NUT_PATH', realpath(TEST_ROOT . '/app/nut'));
}



if (!defined('EXTENSION_AUTOLOAD')) {
    define('EXTENSION_AUTOLOAD',  realpath(dirname(__DIR__) . '/vendor/autoload.php'));
}

if (!defined('BOOKME_EXTENSION_PATH')) {
    define('BOOKME_EXTENSION_PATH',  realpath('/../'.dirname(__DIR__)));
}

// Vendor Auto Load
require_once __DIR__.'/../vendor/autoload.php';

require_once EXTENSION_AUTOLOAD;




