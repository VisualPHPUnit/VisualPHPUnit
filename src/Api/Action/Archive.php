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
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Application $app)
    {
        $suites = Suite::getAll($app['db']);
        $data = [];
        foreach ($suites as $suite) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $suite['executed']);
            $data['snapshots'][] = [
                'date' => $date->format('Y-m-d H:i:s')
            ];
        }
        
        return $this->ok($data);
    }
}
