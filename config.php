<?php

ini_set('display_errors', 1);

define('PHPUNIT_INSTALL', '/usr/lib/php/');

set_include_path(get_include_path().PATH_SEPARATOR.
                 PHPUNIT_INSTALL.PATH_SEPARATOR);

define("BASE_INSTALL", realpath(__DIR__)); 

define('TEST_DIRECTORY', 'tests');

define('TEST_FILENAME', 'Test');

define('CREATE_SNAPSHOTS', false);

define('SNAPSHOT_DIRECTORY', 'history');

define('SANDBOX_ERRORS', true);

define('SANDBOX_FILENAME', BASE_INSTALL . '/errors.tmp');

?>
