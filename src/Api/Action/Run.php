<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.6<
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2016 VisualPHPUnit
 * @license http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace Visualphpunit\Api\Action;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Visualphpunit\Core\Parser;
use Visualphpunit\Core\Suite;
use Visualphpunit\Core\Test;

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Application $app)
    {
        Suite::createTable($app['db']);
        Test::createTable($app['db']);

        $data = json_decode($request->getContent(), true);

        if (count($data['files'])) {
            $parser = new Parser();
            $result = $parser->run($data['files']);
            if ($data['config']['snapshot']) {
                Suite::store($app['db'], $result);
            }
            Test::store($app['db'], $result);
            return $this->ok([
                $result
            ]);
        }
        return $this->ok([
            'nofiles'
        ]);
    }
}
