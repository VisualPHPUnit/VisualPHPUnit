<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.3<
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2015 VisualPHPUnit
 * @license http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace Visualphpunit\Api\Application;

use Silex\Application;
use Visualphpunit\Api\Controller\Vpu as VpuController;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
use Igorw\Silex\ConfigServiceProvider;

/**
 * Visualphpunit Rest Api application
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class Vpu extends Application
{

    /**
     * Bootstrap the application
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $app = $this;
        $app['debug'] = true;
        $app->register(new ConfigServiceProvider("../vpu.json"));
        $app->register(new CorsServiceProvider(), array(
            "cors.allowOrigin" => "*"
        ));
        $app->after($app["cors"]);
        $app->mount('/', new VpuController());
    }
}