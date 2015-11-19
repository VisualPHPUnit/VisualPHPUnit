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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Application $app)
    {
        $start = new DateTime();
        $start->setDate($start->format('Y'), $start->format('m'), 1);
        $end = new DateTime();
        $end->setDate($end->format('Y'), $end->format('m'), $start->format('t'));
        
        $interval = $start->diff($end);
        $range = (int) $interval->format('%a') + 1;
        
        $data = Test::getByDay($app['db'], $start, $end);
        $data = static::explodeTests($data);
        $data = static::toPeriode($data, $range);

        return $this->ok($data);
    }

    private static function toPeriode($data, $range)
    {
        foreach ($data as $status => $test) {
            for ($i = 1; $i <= $range; $i ++) {
                if (! isset($test[$i])) {
                    $data[$status][$i] = 0;
                } else {
                    $data[$status][$i] = (int) $data[$status][$i][0];
                }
            }
            ksort($data[$status], SORT_NATURAL);
        }
        
        return $data;
    }

    private static function explodeTests($data)
    {
        $periode = [];
        foreach ($data as $day) {
            switch ($day['status']) {
                case 'passed':
                    $periode['passed'][$day['day']] = [
                        $day['number']
                    ];
                    break;
                case 'failed':
                    $periode['failed'][$day['day']] = [
                        $day['number']
                    ];
                    break;
                case 'notImplemented':
                    $periode['notImplemented'][$day['day']] = [
                        $day['number']
                    ];
                    break;
                case 'skipped':
                    $periode['skipped'][$day['day']] = [
                        $day['number']
                    ];
                    break;
                case 'error':
                    $periode['error'][$day['day']] = [
                        $day['number']
                    ];
                    break;
                default:
                    break;
            }
        }
        
        return $periode;
    }
}
