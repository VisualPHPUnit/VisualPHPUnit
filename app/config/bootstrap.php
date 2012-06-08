<?php

$config = array(
    'pear_path' => '/usr/share/pear',
    'db'        => array(
        'plugin'   => '\app\lib\PDO_MySQL',
        'database' => 'vpu',
        'host'     => 'localhost',
        'port'     => '3306',
        'username' => 'root',
        'password' => 'admin'
    )
);


$root = PATH_SEPARATOR . dirname(dirname(__DIR__));
set_include_path(
    get_include_path()
    . $root
    . PATH_SEPARATOR . $config['pear_path']
);

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Util/Log/JSON.php';

spl_autoload_register(function($class) {
    $file = str_replace('\\', '/', $class) . '.php';
    require $file;
});

\app\lib\Connections::add_db($config['db']);

?>
