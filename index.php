<?php

require 'inc/bootstrap.php';

// define('APP_DIR', 'app');
// define('DS', DIRECTORY_SEPARATOR);
// define('ROOT', dirname(__FILE__));

use Inc\Request\Request;
use Inc\Router\Router;
(new Router())->load('config/routes.php')
      ->direct(Request::uri(), Request::method());