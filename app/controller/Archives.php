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

/**
 * Archives
 *
 * Class for managing archives
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class Archives extends \app\core\Controller
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
        $snapshot_directory = Library::retrieve('snapshot_directory');
        if (! $request->is('ajax')) {
            $snapshots = array();
            $handler = @opendir($snapshot_directory);
            if (! $handler) {
                return compact('snapshots');
            }
            while (($file = readdir($handler)) !== false) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (strpos($file, '.') === 0 || $ext != 'html') {
                    continue;
                }
                $snapshots[] = $file;
            }
            closedir($handler);
            rsort($snapshots);
            
            return compact('snapshots');
        }
        
        if (! isset($request->query['snapshot'])) {
            return '';
        }
        
        $file = realpath($snapshot_directory) . "/{$request->query['snapshot']}";
        return (file_exists($file)) ? file_get_contents($file) : '';
    }
}
