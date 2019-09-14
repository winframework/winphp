<?php

use Win\Mvc\Router;

/**
 * url => NomeDoController/nomeDoAction
 * 'home' => 'IndexController/helloWorld'
 */
Router::$routes = [
	'' => 'IndexController/index',
	'home' => 'IndexController/index',

	'pages' => 'PagesController/index/$1',
	'pages/(.*)' => 'PagesController/byCategory/$1',
	'page/(.*)' => 'PagesController/detail/$1',

	'contato' => 'ContatoController/index',
	'contato/(.*)' => 'ContatoController/index',
	'json-api' => 'JsonApiController/index',
	'other-page/(.*)' => 'DemoController/index/$1',
];
