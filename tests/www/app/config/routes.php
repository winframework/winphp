<?php

use Win\Mvc\Router;

/**
 * url => NomeDoController/nomeDoAction
 */
Router::instance()->setRoutes([
	'other-page/(.*)' => 'DemoController/index/$1',
	'' => 'IndexController/index',

	'pages/(.*)' => 'PagesController/byCategory/$1',
	'page/(.*)' => 'PagesController/detail/$1',

	'contato' => 'ContatoController/index',
	'contato/(.*)' => 'ContatoController/index',
]);
