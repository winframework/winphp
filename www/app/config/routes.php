<?php

use Win\Mvc\Router;

/*
 * Direciona uma URL para um Controller/action
 *
 * url => NomeDoController/nomeDoAction
 */
Router::$routes = [
	'' => 'IndexController@index',
];
