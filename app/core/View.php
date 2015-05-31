<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.3<
 *
 * @author    Nick Sinopoli <NSinopoli@gmail.com>
 * @copyright 2011-2015 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace app\core;

use \RuntimeException;

/**
 * View
 *
 * View class for MVC
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class View
{

    /**
     * Configuration options
     *
     * @var array
     */
    protected $config;

    /**
     * Loads the configuration settings for the view.
     *
     * @param array $config
     *            The configuration options.
     */
    public function __construct(array $config = array())
    {
        $defaults = array(
            'dependencies' => array(
                'compiler' => 'app\lib\Compiler'
            )
        );
        $this->config = $config + $defaults;
    }

    /**
     * Escapes a value for output in an HTML context.
     *
     * @param mixed $value
     *            Value to escape
     * @return mixed
     */
    public function escape($value)
    {
        return nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Renders a given file with the supplied variables.
     *
     * @param string $file
     *            The file to be rendered.
     * @param mixed $vars
     *            The variables to be substituted in the view.
     * @return string
     */
    public function render($file, $vars = null)
    {
        $path = dirname(__DIR__) . '/resource/cache/';
        $file = dirname(__DIR__) . "/view/{$file}.html";
        
        $compiler = $this->config['dependencies']['compiler'];
        $options = compact('path');
        $__template__ = $compiler::compile($file, $options);
        $message = "Could not write cache file. Please ensure that the permissions of {$path} are correct";
        if (! $__template__) {
            throw new RuntimeException($message);
        }
        
        if (is_array($vars)) {
            extract($vars);
        }
        
        ob_start();
        require $__template__;
        return ob_get_clean();
    }
}
