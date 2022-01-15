<?php

namespace Inc\Router;

interface RouterInterface
{
    /**
     * Load a user's routes file.
     *
     * @param string $file
     *
     * @return RouterInterface
     */
    public function load($file);

    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param string $controller
     */
    public function get($uri, $controller);

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param string $controller
     */
    public function post($uri, $controller);

    /**
     * Load the requested URI's associated controller method.
     *
     * @param string $uri
     * @param string $requestType
     *
     * @return mixed
     */
    public function direct($uri, $requestType);
}
