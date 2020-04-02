<?php

use Win\Request\Router;

/*
 * Direciona uma URL para um Controller/action
 *
 * url => NomeDoController/nomeDoAction
 */
Router::addRoutes('App\\Controllers\\', [
	'' => 'IndexController@index',

	// Básicos
	'basic' => 'BasicController@index',
	'alternative-layout' => 'BasicController@alternativeLayout',
	'basic-class' => 'BasicController@classView',
	'return-five' => 'BasicController@returnFive',
	'other-page/(.*)' => 'BasicController@index',
	'private' => 'BasicController@methodPrivate',
	'json-api' => 'BasicController@json',
	'alerts/show' => 'BasicController@showAlerts',
	'alerts' => 'BasicController@createAlerts',
	'redirecting' => 'BasicController@redirecting',

	// 404
	'controller404' => 'IndexControllasdf',
	'action404' => 'BasicController@naoExiste',
	'not-found1' => 'BasicController@notFound1',
	'not-found2' => 'BasicController@notFound2',

	// Namespace
	'namespace' => 'MyModule\IndexController@index',

	// Avançados
	'send-email' => 'EmailController@index',
	'uploader' => 'UploaderController@index',
	'database' => 'DatabaseController@index',

	// Pages
	'pages' => 'PagesController@index',
	'pages/(.*)' => 'PagesController@byCategory',
	'page/(.*)' => 'PagesController@detail',

	// Contato
	'contato' => 'ContatoController@index',
]);
