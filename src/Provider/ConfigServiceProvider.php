<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.6<
 *
 * @author    Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2016 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace Visualphpunit\Provider;

use Pimple\Container;
use Silex\Application;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;

/**
 * Visualphpunit Silex Config provider
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class ConfigServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{

    /**
     * Path to config file
     *
     * @var string
     */
    private $path;

    /**
     * Specify the path to the config file
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Register the provider
     *
     * @param Application $app
     * @return \Carpet\Provider\Jira
     */
    public function register(Container $app)
    {
        $app["config"] = json_decode(file_get_contents($this->path), true)['config'];
    }

    /**
     * Boot the provider
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
