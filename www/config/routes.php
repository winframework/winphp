<?php

use Win\Request\Router;

/*
 * Direciona uma URL para um Controller/action
 *
 * url => NomeDoController/nomeDoAction
 */
Router::routes('App\\Controllers\\', [
	'' => 'IndexController@index',
]);
