<?php

namespace Inc\Router;

use Inc\Response\Response;

class Router implements RouterInterface
{
    /**
     * All registered routes.
     *
     * @var array
     */
    public $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    /**
     * Load a user's routes file.
     *
     * @param string $file
     *
     * @return Router
     */
    public function load($file)
    {
        $router = new static();

        require $file;

        return $router;
    }

    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param string $controller
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param string $controller
     */
    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }    

    /**
     * Load the requested URI's associated controller method.
     *
     * @param string $uri
     * @param string $requestType
     *
     * @return mixed
     */
    public function direct($uri, $requestType)
    {
        if (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT']) {
            if (array_key_exists($uri, $this->routes[$requestType])) {
                return $this->callAction(
                    ...explode('@', $this->routes[$requestType][$uri])
                );
            }
        } else {
            $message = 'The API accepts only requests with an accept header.';
            return $this->response(false, $message, Response::HTTP_NOT_ACCEPTABLE);
        }
        $message = 'No route defined for this URI.';
        return $this->response(false, $message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Load and call the relevant controller action.
     *
     * @param string $controller
     * @param string $action
     *
     * @return mixed
     */
    protected function callAction($controller, $action)
    {
        $controller = "App\\Controllers\\{$controller}";
        $controller = new $controller();

        if (!method_exists($controller, $action)) {
            $message ="{$controller} does not respond to the {$action} action.";
            return $this->response(false, $message, Response::HTTP_NOT_FOUND);          
        }

        return $controller->$action();
    }

    protected function response($ok = false, $message = null, $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $data['ok'] = $ok;
        $data['message'] = $message;
        $response = new Response();
        $response->responseJson($data, $statusCode);
    }
}
