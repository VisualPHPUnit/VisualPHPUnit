<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.6<
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2015 VisualPHPUnit
 * @license http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace Visualphpunit\Api\Action;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Visualphpunit\Core\Parser;
use Visualphpunit\Core\Suite;

/**
 * Visualphpunit run tests action
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class Run extends Action
{

    /**
     * Run the selected test files
     *
     * Run the selected test with phpunit
     *
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Application $app)
    {
        //Suite::dropTable($app['db']);
        Suite::createTable($app['db']);
        
        $data = json_decode($request->getContent(), true);
        if (count($data)) {
            $parser = new Parser($app['db']);
            $result = $parser->run($data);
            Suite::store($app['db'], $result);
            return $this->ok([
                $result
            ]);
        }
        return $this->ok([
            'nofiles'
        ]);
    }
}
