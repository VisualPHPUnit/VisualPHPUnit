<?php

namespace app\lib;

class Connections {

   /**
    *  The options for handlers.
    *
    *  @var array
    *  @access protected
    */
    protected static $_options = array(
        'db' => array()
    );

   /**
    * Stores the database connection details using the defined options.
    *
    * @param array $config    The database configuration.  Takes the following
    *                         parameters:
    *                         'plugin'   - The database plugin.
    *                         'database' - The database name.
    *                         'host'     - The database host.
    *                         'username' - The database username.
    *                         'password' - The database password.
    *                         'port'     - The database port.
    * @access public
    * @return void
    */
    public static function add_db($config = array()) {
        self::$_options['db'] = $config;
    }

   /**
    * Returns the database options.
    *
    * @access public
    * @return object
    */
    public static function get_db() {
        return self::$_options['db'];
    }
}

?>
