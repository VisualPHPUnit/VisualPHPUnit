<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.6<
 *
 * @author    Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2015 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace Visualphpunit\Api\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;

/**
 * Visualphpunit main controller
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class Vpu implements ControllerProviderInterface
{

    /**
     * (non-PHPdoc)
     *
     * @param Application $app            
     *
     * @see \Silex\ControllerProviderInterface::connect()
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', 'Visualphpunit\Api\Action\Index::index');
        $controllers->get('/archives', 'Visualphpunit\Api\Action\Archive::index');
        $controllers->get('/graphs', 'Visualphpunit\Api\Action\Graph::index');
        $controllers->get('/tests', 'Visualphpunit\Api\Action\Test::index');
        $controllers->post('/run', 'Visualphpunit\Api\Action\run::index');
        $controllers->get('/run', 'Visualphpunit\Api\Action\run::index');
        return $controllers;
    }
}