<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.3<
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2015 VisualPHPUnit
 * @license http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace Visualphpunit\Api\Action;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;

class File extends Action
{

    /**
     *
     * @param Request $request            
     * @param Application $app            
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Application $app)
    {
        $data = array();
        foreach ($app['config']['test-directories'] as $suite) {
            $data[] = array(
                'text' => $suite['name'],
                'type' => 'suite',
                'nodes' => $this->parse($suite['path'], $suite['ignoreHidden']),
                'selectable' => false
            );
        }
        return $this->ok($data);
    }

    /**
     * Parse the path for files
     *
     * @param string $paths            
     * @param boolean $ignoreHidden            
     *
     * @return array
     */
    private function parse($dir, $ignoreHidden)
    {
        $files = Finder::create()->ignoreDotFiles($ignoreHidden)
            ->sortByType()
            ->depth(0)
            ->name('*.php')
            ->in($dir);
        $directories = Finder::create()->ignoreDotFiles($ignoreHidden)
            ->sortByType()
            ->depth(0)
            ->directories()
            ->append($files)
            ->in($dir);
        
        $list = array();
        
        foreach ($directories as $file) {
            if ($file->getType() == 'dir') {
                $list[] = array(
                    'text' => $file->getBasename('.php'),
                    'type' => $file->getType(),
                    'path' => $file->getRealPath(),
                    'nodes' => $this->parse($file->getRealPath(), $ignoreHidden),
                    'selectable' => false
                );
            } else {
                $list[] = array(
                    'text' => $file->getBasename('.php'),
                    'type' => $file->getType(),
                    'path' => $file->getRealPath(),
                    'selectable' => true,
                    'tags' => $this->getNumberOfMethods($file->getRealPath())
                );
            }
        }
        
        return $list;
    }

    /**
     * Get number of methods in test class
     *
     * @todo likely there are better ways of doing this
     * @todo make sure the file is a test file
     * @param string $path            
     *
     * @return interger
     */
    private function getNumberOfMethods($path)
    {
        $result1 = preg_grep('/^namespace/', file($path));
        preg_match('/^namespace\s([\\A-Za-z0-9]+).+$/', array_pop($result1), $matches1);
        $namespace = $matches1[1];
        $result2 = preg_grep('/^class/', file($path));
        preg_match('/^class\s([A-Za-z0-9]+).+$/', array_pop($result2), $matches2);
        $class = $matches2[1];
        require_once $path;
        $some = new \ReflectionClass($namespace . '\\' . $class);
        $methods = [];
        foreach ($some->getMethods() as $method) {
            if ($method->class == 'visualphpunit\\' . $class) {
                if ($method->isPublic()) {
                    $methods[] = $method->name;
                }
            }
        }
        return [
            count($methods)
        ];
    }
}
