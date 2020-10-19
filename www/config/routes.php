<?php

use App\Controllers\IndexController;
use Win\Services\Router;

$router =  Router::instance();

/**
 * Direciona uma URL para um Controller
 *
 * url => [NomeDoController, nomeDoAction]
 */
$router->routes = [
	'' => [IndexController::class, 'index'],
];
