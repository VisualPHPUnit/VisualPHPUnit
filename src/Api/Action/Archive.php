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
use Visualphpunit\Core\Suite;
use \DateTime;

/**
 * Visualphpunit archive action
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class Archive extends Action
{

    /**
     * Get archived test suites
     *
     * Get all archived test suites
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Application $app)
    {
        $suites = Suite::getSnapshots($app['db']);
        
        return $this->ok($this->parse($suites));
    }

    /**
     * Get archived test suite
     *
     * Get archived test suite data
     *
     * @param integer $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function suite($id, Request $request, Application $app)
    {
        $suite = Suite::getSuite($app['db'], $id);
        return $this->ok($suite);
    }

    /**
     * Parse list of suites
     *
     * Parse list of suites to a tree structure
     *
     * @param mixed[] $suites
     *
     * @return string[][]|boolean[][]|NULL[][]
     */
    private function parse($suites)
    {
        $list = [];
        
        foreach ($suites as $suite) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $suite['executed']);
            $list[] = array(
                'text' => $date->format('Y-m-d H:i:s'),
                'type' => 'suite',
                'id' => $suite['id'],
                'selectable' => true
            );
        }
        return $list;
    }
}
