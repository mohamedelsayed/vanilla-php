<?php

namespace Inc\Response;

/**
 * Response represents an HTTP response.
 */
class Response
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_TOO_MANY_REQUESTS = 429;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public static $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
    ];

    public function setHeaderWithStatusCode($statusCode)
    {
        header('Content-Type: application/json');
        header("HTTP/1.1 " . $statusCode . " " . self::$statusTexts[$statusCode]);
    }    
    
    /**
    * write header to accepjson.
    *
    * @param array $data
    * @param int $statusCode
    *
    */
    public function responseJson($data = [], $statusCode = Response::HTTP_OK)
    {
        $this->setHeaderWithStatusCode($statusCode);
        echo json_encode($data);
    }
}
