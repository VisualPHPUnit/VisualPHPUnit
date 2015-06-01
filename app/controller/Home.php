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

use \app\lib\Library;
use \app\lib\VPU;
use \app\core\Controller;

/**
 * Home
 *
 * Class for managing homepage
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class Home extends Controller
{

    /**
     * Create a snapshot
     *
     * @param array $view_data
     *            View data
     * @return array
     */
    protected function createSnapshot($view_data)
    {
        $directory = Library::retrieve('snapshot_directory');
        $filename = realpath($directory) . '/' . date('Y-m-d_H-i') . '.html';
        
        $contents = $this->renderHtml('partial/test_results', $view_data);
        
        $handle = @fopen($filename, 'a');
        if (! file_exists($directory) || ! $handle) {
            $message = 'Please ensure that the <code>snapshot_directory</code> ';
            $message += 'in <code>app/config/bootstrap.php</code> exists and has the proper permissions.';
            return array(
                'type' => 'failed',
                'title' => 'Error Creating Snapshot',
                'message' => $message
            );
        }
        
        fwrite($handle, $contents);
        fclose($handle);
        return array(
            'type' => 'succeeded',
            'title' => 'Snapshot Created',
            'message' => "Snapshot can be found at <code>{$filename}</code>."
        );
    }

    /**
     * help
     *
     * @param string $request
     *            The request to process
     * @return array
     */
    public function help($request)
    {
        return array();
    }

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
            $normalize_path = function ($path) {
                return str_replace('\\', '/', realpath($path));
            };
            $test_directories = Library::retrieve('test_directories');
            $test_directories = json_encode(array_map($normalize_path, array_values($test_directories)));
            
            $suites = array();
            $stats = array();
            $store_statistics = Library::retrieve('store_statistics');
            $create_snapshots = Library::retrieve('create_snapshots');
            $sandbox_errors = Library::retrieve('sandbox_errors');
            $xml_configuration_files = Library::retrieve('xml_configuration_files');
            return compact(
                'create_snapshots',
                'sandbox_errors',
                'stats',
                'store_statistics',
                'suites',
                'test_directories',
                'xml_configuration_files'
            );
        }
        
        $tests = explode('|', $request->data['test_files']);
        $vpu = new VPU();
        
        if ($request->data['sandbox_errors']) {
            error_reporting(Library::retrieve('error_reporting'));
            set_error_handler(array(
                $vpu,
                'handleErrors'
            ));
        }
        
        $xml_config = false;
        
        $notifications = array();
        if ($xml_file_index = $request->data['xml_configuration_file']) {
            $files = Library::retrieve('xml_configuration_files');
            $xml_config = $files[$xml_file_index - 1];
            if (! $xml_config || ! $xml_config = realpath($xml_config)) {
                $message = 'Please ensure that the <code>xml_configuration_file</code> in ';
                $message += '<code>app/config/bootstrap.php</code> exists and has the proper permissions.';
                $notifications[] = array(
                    'type' => 'failed',
                    'title' => 'No Valid XML Configuration File Found',
                    'message' => $message
                );
            }
        }
        
        $results = ($xml_config) ? $vpu->runWithXml($xml_config) : $vpu->runTests($tests);
        $results = $vpu->compileSuites($results, 'web');
        
        if ($request->data['sandbox_errors']) {
            restore_error_handler();
        }
        
        $suites = $results['suites'];
        $stats = $results['stats'];
        $errors = $vpu->getErrors();
        $to_view = compact('suites', 'stats', 'errors');
        
        if ($request->data['create_snapshots']) {
            $notifications[] = $this->createSnapshot($to_view);
        }
        if ($request->data['store_statistics']) {
            $notifications[] = $this->storeStatistics($stats);
        }
        
        return $to_view + compact('notifications');
    }

    /**
     * Stro statistics
     *
     * @param array $stats
     *            Stats to process
     *
     * @return array
     */
    protected function storeStatistics($stats)
    {
        $db_options = Library::retrieve('db');
        $db = new $db_options['plugin']();
        if (! $db->connect($db_options)) {
            return array(
                'type' => 'failed',
                'title' => 'Error Connecting to Database',
                'message' => implode(' ', $db->getErrors())
            );
        }
        
        $now = date('Y-m-d H:i:s');
        foreach ($stats as $key => $stat) {
            $data = array(
                'run_date' => $now,
                'failed' => $stat['failed'],
                'incomplete' => $stat['incomplete'],
                'skipped' => $stat['skipped'],
                'succeeded' => $stat['succeeded']
            );
            $table = ucfirst(rtrim($key, 's')) . 'Result';
            if (! $db->insert($table, $data)) {
                return array(
                    'type' => 'failed',
                    'title' => 'Error Inserting Record',
                    'message' => implode(' ', $db->getErrors())
                );
            }
        }
        
        return array(
            'type' => 'succeeded',
            'title' => 'Statistics Stored',
            'message' => 'The statistics generated during this test run were ' . 'successfully stored.'
        );
    }
}
