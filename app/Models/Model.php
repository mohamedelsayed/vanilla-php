<?php

namespace App\Models;

use Inc\App;
use Inc\Database\QueryBuilder;

abstract class Model extends QueryBuilder
{
    /**
     * Model constructor.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        parent::__construct(App::get('database'));
    }
}
