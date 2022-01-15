<?php

require 'inc/bootstrap.php';

use Inc\Request\Request;
use Inc\Router\Router;
(new Router())->load('config/routes.php')
      ->direct(Request::uri(), Request::method());