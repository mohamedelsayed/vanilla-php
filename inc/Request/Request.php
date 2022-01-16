<?php

namespace Inc\Request;

class Request implements RequestInterface
{
    /**
     * The request data.
     *
     * @var array
     */
    protected $data;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->data = $_REQUEST;
    }

    /**
     * Get parameter from the global request array.
     *
     * @param string      $key
     * @param string|null $default
     *
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        $value = $default;
        if (isset($this->data[$key])) {
            $value = $this->data[$key];
        }
        $value = $this->sanitizeInput($value);
        return $value;
    }

    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri()
    {
        return trim(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            '/'
        );
    }

    /**
     * Fetch the request method.
     *
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    protected function sanitizeInput($input)
    {
        $input = filter_var($input, FILTER_SANITIZE_STRING);
        return $input;
    }
}
