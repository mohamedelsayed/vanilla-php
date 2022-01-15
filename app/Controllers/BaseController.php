<?php

namespace App\Controllers;

use Inc\App;

abstract class BaseController
{
    /**
     * @var App
     */
    protected $container;

    /**
     * BaseController constructor for initiating the service container.
     */
    public function __construct()
    {
        $this->container = App::class;
    }

}
