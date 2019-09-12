<?php

use Win\Mvc\Router;

/**
 * url => NomeDoController/nomeDoAction
 */
Router::instance()->setRoutes([
	'other-page/(.*)' => 'DemoController/index/$1',

	'pages/(.*)' => 'PagesController/byCategory/$1',
	'page/(.*)' => 'PagesController/detail/$1',
]);
