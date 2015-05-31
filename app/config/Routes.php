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
namespace app\config;

/**
 * Routes
 *
 * Routing class for http requests
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class Routes
{

    /**
     * Gret routes
     *
     * @return array
     */
    public static function getRoutes()
    {
        return array(
            array(
                array(
                    'get',
                    'post'
                ),
                '/',
                function ($request) {
                    $controller = new \app\controller\Home();
                    return $controller->call('index', $request);
                }
            ),
            
            array(
                'get',
                '/archives',
                function ($request) {
                    $controller = new \app\controller\Archives();
                    return $controller->call('index', $request);
                }
            ),
            
            array(
                array(
                    'get',
                    'post'
                ),
                '/graphs',
                function ($request) {
                    $controller = new \app\controller\Graph();
                    return $controller->call('index', $request);
                }
            ),
            
            array(
                'get',
                '/file-list',
                function ($request) {
                    $controller = new \app\controller\FileList();
                    return $controller->call('index', $request);
                }
            ),
            
            array(
                'get',
                '/help',
                function ($request) {
                    $controller = new \app\controller\Home();
                    return $controller->call('help', $request);
                }
            ),
            
            // 404
            array(
                'get',
                '*',
                function ($request) {
                    return array(
                        'status' => 404,
                        'body' => '<h1>Not Found</h1>'
                    );
                }
            )
        );
    }
}
