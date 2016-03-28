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
use Visualphpunit\Core\Test;
use \DateTime;

/**
 * Visualphpunit graph action
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class Graph extends Action
{

    /**
     * Get graph data
     *
     * Get graph data from test resuts
     *
     * @param string $unit            
     * @param string $start            
     * @param string $end            
     * @param \Symfony\Component\HttpFoundation\Request $request            
     * @param \Silex\Application $app            
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($unit, $start, $end, Request $request, Application $app)
    {
        $start = DateTime::createFromFormat('Y-m-d', $start);
        $start->setTime(0, 0, 0);
        $end = DateTime::createFromFormat('Y-m-d', $end);
        $end->setTime(23, 59, 59);
        
        $data = null;
        
        switch ($unit) {
            case 'h':
                $data = Test::getTests($app['db'], $start, $end, Test::GROUP_BY_HOUR);
                break;
            case 'd':
                $data = Test::getTests($app['db'], $start, $end, Test::GROUP_BY_DAY);
                break;
            case 'm':
                $data = Test::getTests($app['db'], $start, $end, Test::GROUP_BY_MONTH);
                break;
            case 'y':
                $data = Test::getTests($app['db'], $start, $end, Test::GROUP_BY_YEAR);
                break;
            default:
                break;
        }
        $data = static::explodeTests($data);
        return $this->ok($data);
    }

    private static function explodeTests($data)
    {
        $periode = [];
        foreach ($data as $test) {
            switch ($test['status']) {
                case 'passed':
                    $periode['passed'][$test['unit']] = [
                        (int) $test['number']
                    ];
                    break;
                case 'failed':
                    $periode['failed'][$test['unit']] = [
                        (int) $test['number']
                    ];
                    break;
                case 'notImplemented':
                    $periode['notImplemented'][$test['unit']] = [
                        (int) $test['number']
                    ];
                    break;
                case 'skipped':
                    $periode['skipped'][$test['unit']] = [
                        (int) $test['number']
                    ];
                    break;
                case 'error':
                    $periode['error'][$test['unit']] = [
                        (int) $test['number']
                    ];
                    break;
                default:
                    break;
            }
        }
        return $periode;
    }
}
