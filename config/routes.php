<?php
/**
 * @var \Inc\Router
 */

$router->post('users', 'UsersController@store');
$router->post('reset-password', 'UsersController@resetPassword'); //TODO add rate limit for this critical api etc 3 per hour
$router->post('set-password', 'UsersController@setPassword'); //TODO add rate limit for this critical api etc 3 per hour
