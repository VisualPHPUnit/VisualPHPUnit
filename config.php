<?php

ini_set('display_errors', 1);

define('TEST_FILENAME', 'Test');

define('PHPUNIT_INSTALL', '/usr/lib/php/');

define('TEST_DIRECTORY', 'tests');

set_include_path(get_include_path().PATH_SEPARATOR.
                 PHPUNIT_INSTALL.PATH_SEPARATOR);
?>
