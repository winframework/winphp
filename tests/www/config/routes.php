<?php

use Win\Request\Router;

/*
 * Direciona uma URL para um Controller/action
 *
 * url => NomeDoController/nomeDoAction
 */
Router::routes('App\\Controllers\\', [
	'' => 'IndexController@index',

	// Pages
	'pages' => 'PageController@index',
	'pages/(.*)' => 'PageController@byCategory',
	'page/(.*)' => 'PageController@detail',

	// Contato
	'contato' => 'ContatoController@index',
	'contato/send' => 'ContatoController@send',

	// Exemplos
	'demo' => 'DemoController@index',
	'view-set-values' => 'DemoController@viewSetValues',
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
]);