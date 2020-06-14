<?php

use Win\Request\Router;

/**
 * Direciona uma URL para um Controller/action
 *
 * url => NomeDoController/nomeDoAction
 */
Router::add('App\\Controllers\\', [
	'' => 'IndexController@index',
]);
