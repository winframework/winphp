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

	// Exemplos
	'demo' => 'DemoController@index',
	'database' => 'DatabaseController@index',
	'alerts' => 'AlertsController@index',
	'alerts/show' => 'AlertsController@show',
	'redirect' => 'RedirectController@index',
	'uploader' => 'UploaderController@index',
	'return-five' => 'DemoController@returnFive',
	'namespace' => 'MyModule\IndexController@index',
	'nao-existe' => 'IndexControllasdf',

	// Json
	'json-api' => 'JsonApiController@index',

	// Demo
	'other-page/(.*)' => 'DemoController@index',
];
