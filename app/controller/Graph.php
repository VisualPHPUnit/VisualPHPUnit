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
namespace app\controller;

use \app\core\Controller;
use \app\lib\Library;

/**
 * Graph
 *
 * Class for managing graphs
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class Graph extends Controller
{

    /**
     * Index
     *
     * @param string $request
     *            The request to process
     * @return array
     */
    public function index($request)
    {
        if ($request->is('get')) {
            return array();
        }
        
        $table = "{$request->data['graph_type']}Result";
        if (! $request->data['start_date'] ||
            ! $request->data['end_date'] ||
            ($table != 'SuiteResult' && $table != 'TestResult')) {
            return array(
                'type' => $request->data['graph_type'],
                'timeFrame' => $request->data['time_frame'],
                'categories' => array(),
                'failed' => 0,
                'succeeded' => 0,
                'skipped' => 0,
                'incomplete' => 0
            );
        }
        
        $db_options = Library::retrieve('db');
        $db = new $db_options['plugin']();
        if (! $db->connect($db_options)) {
            return array(
                'error' => array(
                    'title' => 'Error Connecting to Database',
                    'message' => implode(' ', $db->getErrors())
                )
            );
        }
        
        switch ($request->data['time_frame']) {
            case 'Monthly':
                $interval = 2678400;
                $sql_format = 'Y-m-01';
                $output = 'M Y';
                break;
            case 'Weekly':
                $interval = 604800;
                $sql_format = 'Y-m-d';
                $output = 'm/d';
                break;
            default:
                $interval = 86400;
                $sql_format = 'Y-m-d';
                $output = 'm/d';
                break;
        }
        $current = strtotime($request->data['start_date']);
        $end = strtotime($request->data['end_date']) + $interval;
        
        $categories = array();
        $plot_values = array(
            'failed' => array(),
            'incomplete' => array(),
            'skipped' => array(),
            'succeeded' => array()
        );
        while ($current < $end) {
            $categories[] = date($output, $current);
            $next = $current + $interval;
            
            $data = array(
                'failed' => 0,
                'incomplete' => 0,
                'skipped' => 0,
                'succeeded' => 0
            );
            
            $sql = "select failed, incomplete, skipped, succeeded from {$table} where run_date >= ? and run_date < ?";
            $params = array(
                date($sql_format, $current),
                date($sql_format, $next)
            );
            $db->query($sql, $params);
            
            $results = $db->fetchAll();
            $num_rows = count($results);
            
            if ($num_rows > 0) {
                foreach ($results as $result) {
                    foreach ($result as $key => $value) {
                        $data[$key] += $value;
                    }
                }
            }
            
            foreach ($data as $key => $val) {
                if ($num_rows > 0) {
                    $plot_values[$key][] = round($val / $num_rows, 2);
                } else {
                    $plot_values[$key][] = 0;
                }
            }
            
            $current = $next;
        }
        
        $db->close();
        
        return array(
            'type' => $request->data['graph_type'],
            'timeFrame' => $request->data['time_frame'],
            'categories' => $categories,
            'failed' => $plot_values['failed'],
            'succeeded' => $plot_values['succeeded'],
            'skipped' => $plot_values['skipped'],
            'incomplete' => $plot_values['incomplete']
        );
    }
}
