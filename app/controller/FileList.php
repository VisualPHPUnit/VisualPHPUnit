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
 * FileList
 *
 * Class for managing file lists
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class FileList extends Controller
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
        if (! $request->is('ajax')) {
            return $this->redirect('/');
        }
        
        $dir = realpath(urldecode($request->query['dir']));
        if (! $dir) {
            return array();
        }
        
        $test_directories = Library::retrieve('test_directories');
        $valid_dir = false;
        $group_name = '';
        foreach ($test_directories as $key => $test_directory) {
            if (strpos($dir, realpath($test_directory)) === 0) {
                $group_name = $key;
                $valid_dir = true;
                break;
            }
        }
        
        // echo 'here';
        
        if (! $valid_dir) {
            return array();
        }
        
        $dir .= '/';
        $files = scandir($dir);
        // Don't return anything if 'files' are '.' or '..'
        if (count($files) < 3) {
            return array();
        }
        
        $ignore_hidden = Library::retrieve('ignore_hidden_folders');
        
        $final_dirs = array();
        $final_files = array();
        foreach ($files as $file) {
            $is_hidden = (strpos($file, '.') === 0);
            if ($file != '.' && $file != '..' && (! $is_hidden || ! $ignore_hidden)) {
                $path = $dir . $file;
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (is_dir($path)) {
                    $final_dirs[] = array(
                        'type' => 'directory',
                        'name' => $file,
                        'path' => $path
                    );
                } elseif (is_file($path) && $ext == 'php') {
                    $final_files[] = array(
                        'type' => 'file',
                        'name' => $file,
                        'path' => $path
                    );
                }
            }
        }
        
        return array(
            'name' => $group_name,
            'results' => array_merge($final_dirs, $final_files)
        );
    }
}
