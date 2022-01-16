<?php
/**
 * @author Author "Mohamed Elsayed"
 * @author Author Email "me@mohamedelsayed.net"
 * @link http://www.mohamedelsayed.net
 * @copyright Copyright (c) 2022 Programming by "mohamedelsayed.net"
 */
require 'inc/bootstrap.php';

use Inc\Request\Request;
use Inc\Router\Router;
(new Router())->load('config/routes.php')
      ->direct(Request::uri(), Request::method());