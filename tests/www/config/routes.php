<?php

use Win\Request\Router;

/*
 * Direciona uma URL para um Controller/action
 *
 * url => NomeDoController/nomeDoAction
 */
Router::addRoutes('App\\Controllers\\', [
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
	'class-view' => 'DemoController@classView',
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
