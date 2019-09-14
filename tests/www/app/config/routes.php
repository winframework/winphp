<?php

use Win\Mvc\Router;

/*
 * Direciona uma URL para um Controller@action
 *
 * url => NomeDoController@nomeDoAction
 */
Router::$routes = [
	'' => 'IndexController@index',

	// Pages
	'pages' => 'PagesController@index',
	'pages/(.*)' => 'PagesController@byCategory',
	'page/(.*)' => 'PagesController@detail',

	// Contato
	'contato' => 'ContatoController@index',
	'contato/send' => 'ContatoController@send',

	// Json
	'json-api' => 'JsonApiController@index',
	'other-page/(.*)' => 'DemoController@index',
];
