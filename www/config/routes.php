<?php

use Win\Services\Router;

/**
 * Direciona uma URL para um Controller@action
 *
 * url => NomeDoController@nomeDoAction
 */
Router::instance()->add('App\\Controllers\\', [
	'' => 'IndexController@index',
]);
