<?php

namespace Inc\Request;

interface RequestInterface
{
    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri();

    /**
     * Get parameter from the global request array.
     *
     * @param string      $key
     * @param string|null $default
     *
     * @return mixed|null
     */
    public function get($key, $default = null);

    /**
     * Fetch the request method.
     *
     * @return string
     */
    public static function method();
}
