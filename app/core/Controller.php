<?php

namespace app\core;

class Controller
{

   /**
    *  The class configuration settings.
    *
    *  @var array
    *  @access protected
    */
    protected $config;

   /**
    *  Contains the optional response status and headers.
    *
    *  @var array
    *  @access protected
    */
    protected $response = array();

   /**
    *  Loads the configuration settings for the controller.
    *
    *  @param array $config    The configuration options.
    *  @access public
    *  @return void
    */
    public function __construct(array $config = array())
    {
        $defaults = array(
            'dependencies' => array(
                'view'    => 'app\core\View'
            )
        );
        $this->config = $config + $defaults;
    }

   /**
    *  Primary entry point for all controller actions. The supplied action is
    *  called, returning a response which is then filtered based on the request
    *  source (e.g., xhr, web).
    *
    *  @param string $action    The method to be called.
    *  @param obj $request      The request object.
    *  @access public
    *  @return array
    */
    public function call($action, $request)
    {
        $results = $this->$action($request);

        if (is_null($results) || $results === false) {
            return false;
        }

        if (!is_array($results)) {
            $this->response['body'] = $results;
            return $this->response;
        }

        if ($request->is('ajax')) {
            $this->response['body'] = $this->renderJson($results);
        } else {
            $class = explode('\\', get_called_class());
            $classname = end($class);
            $file = lcfirst($classname) . "/{$action}";
            $this->response['body'] = $this->renderHtml($file, $results);
        }

        return $this->response;
    }

   /**
    *  Redirects the page.
    *
    *  @param string $page    The page to be redirected to.
    *  @access public
    *  @return bool
    */
    public function redirect($page)
    {
        $this->setResponseStatus(303);
        $this->setResponseHeaders(array('Location: ' . $page));
        return '';
    }

   /**
    *  Converts the supplied value to JSON.
    *
    *  @param mixed $value    The value to encode.
    *  @access public
    *  @return string
    */
    public function renderJson($value)
    {
        $options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
        return json_encode($value, $options);
    }

   /**
    *  Renders a view.
    *
    *  @param string $action    The file to be rendered.
    *  @param array $vars       The variables to be substituted in the view.
    *  @access public
    *  @return string
    */
    public function renderHtml($file, $vars = array())
    {
        $view = $this->config['dependencies']['view'];
        $view = new $view();
        return $view->render($file, $vars);
    }

   /**
    *  Sets the response headers.  Note that the supplied headers must be
    *  well-formed HTTP headers.  Example:
    *
    *  $headers = array('Content-Type: text/html; charset=utf-8');
    *
    *  @param array $headers    The response headers.
    *  @access public
    *  @return void
    */
    public function setResponseHeaders($headers)
    {
        $this->response['headers'] = $headers;
    }

   /**
    *  Sets the response status.  Note that the supplied status must be the
    *  integer associated with the HTTP status code (e.g., 404 for Not Found).
    *
    *  @param int $status    The response status.
    *  @access public
    *  @return void
    */
    public function setResponseStatus($status)
    {
        $this->response['status'] = $status;
    }
}
