<?php

require 'vendor/autoload.php';
(new \Dotenv\Dotenv(__DIR__.'/../'))->load();

use Inc\App;
use Inc\Database\Connection;

App::bind('config', require 'config/database.php');
App::bind('database', Connection::make(App::get('config')['database']));
