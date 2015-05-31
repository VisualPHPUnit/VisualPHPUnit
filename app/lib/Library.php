<?php

namespace app\lib;

class Library
{

   /**
    *  The configuration settings.
    *
    *  @var array
    *  @access protected
    */
    protected static $config = array();

   /**
    * Retrieves the configuration options, or a specific option if a key is
    * supplied.
    *
    * @param string $key    The key.
    * @access public
    * @return void
    */
    public static function retrieve($key = null)
    {
        if ($key) {
            return self::$config[$key];
        }
        return self::$config;
    }


   /**
    * Stores the supplied configuration options.
    *
    * @param array $config    The configuration options.
    * @access public
    * @return void
    */
    public static function store($config = array())
    {
        self::$config = $config;
    }
}
