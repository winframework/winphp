<?php

use Win\Request\Router;

/**
 * Direciona uma URL para um Controller/action
 *
 * url => NomeDoController/nomeDoAction
 */
Router::addRoutes('App\\Controllers\\', [
	'' => 'IndexController@index',
]);
