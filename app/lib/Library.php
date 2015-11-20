<?php
/**
 * VisualPHPUnit
 *
 * PHP Version 5.3<
 *
 * @author    Nick Sinopoli <NSinopoli@gmail.com>
 * @copyright 2011-2015 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace app\lib;

/**
 * Library
 *
 * Class for managing configuration options
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class Library
{

    /**
     * The configuration settings.
     *
     * @var array
     */
    protected static $config = array();

    /**
     * Retrieves the configuration options, or a specific option if a key is
     * supplied.
     *
     * @param string $key
     *            The key.
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
     * @param array $config
     *            The configuration options.
     * @return void
     */
    public static function store($config = array())
    {
        self::$config = $config;
    }
}
