<?php

/**
 * NX
 *
 * @author    Nick Sinopoli <NSinopoli@gmail.com>
 * @copyright Copyright (c) 2011-2012, Nick Sinopoli
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace nx\core;

/*
 *  The `Dispatcher` class handles incoming HTTP requests and
 *  sends back responses.
 *
 *  @package core
 */
class Dispatcher {

   /**
    *  The configuration settings.
    *
    *  @var array
    *  @access protected
    */
    protected $_config = array();

   /**
    *  Sets the configuration options for the dispatcher.
    *
    *  @param array $config    The configuration options.
    *  @access public
    *  @return void
    */
    public function __construct(array $config = array()) {
        $defaults = array(
            'dependencies' => array(
                'response' => new \nx\core\Response(),
                'router'   => new \nx\core\Router()
            )
        );
        $this->_config = $config + $defaults;
    }

   /**
    *  Matches an incoming request with the supplied routes,
    *  calls the callback associated with the matched route,
    *  and sends a response.
    *
    *  @param obj $request    The incoming request object.
    *  @param array $routes   The routes.
    *  @access public
    *  @return bool
    */
    public function handle($request, $routes) {
        $method = $request->get_env('REQUEST_METHOD');

        $router = $this->_config['dependencies']['router'];
        $parsed = $router->parse($request->url, $method, $routes);

        if ( $parsed['callback'] ) {
            $request->params = $parsed['params'];
            $result = call_user_func($parsed['callback'], $request);
        } else {
            $result = false;
        }

        $response = $this->_config['dependencies']['response'];
        return $response->render($result);
    }

}

?>
