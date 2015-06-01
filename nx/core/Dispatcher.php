<?php
/**
 * VisualPHPUnit
 *
 * PHP Version 5.3<
 *
 * @author    Nick Sinopoli <NSinopoli@gmail.com>
 * @copyright 2011-2015 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace nx\core;

use \nx\core\Response;
use \nx\core\Router;

/**
 * The Dispatcher handles incoming HTTP requests and sends back responses.
 */
class Dispatcher
{

    /**
     * The configuration settings.
     *
     * @var array
     */
    protected $config = array();

    /**
     * Sets the configuration options for the dispatcher.
     *
     * @param array $config
     *            The configuration options.
     */
    public function __construct(array $config = array())
    {
        $defaults = array(
            'response' => new Response(),
            'router' => new Router()
        );
        $this->config = $config + $defaults;
    }

    /**
     * Matches an incoming request with the supplied routes, calls the
     * callback associated with the matched route, and sends a response.
     *
     * @param object $request
     *            The incoming request object.
     * @param array $routes
     *            The routes.
     * @return void
     */
    public function handle($request, $routes)
    {
        $method = $request->request_method;
        
        $router = $this->config['router'];
        $parsed = $router->parse($request->url, $method, $routes);
        
        if ($parsed['callback']) {
            $request->params = $parsed['params'];
            $result = call_user_func($parsed['callback'], $request);
        } else {
            $result = false;
        }
        
        $response = $this->config['response'];
        $response->render($result);
    }
}
