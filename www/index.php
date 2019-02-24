<?php
/*
 * Para editar o código HTML do layout, procure em: app/blocks/layout/
 * @see app/blocks/layout/
 */
use Win\Mvc\Application;
use Win\Mvc\HttpException;

define('BASE_PATH', __DIR__);

require 'libs/autoload.php';
require 'app/config/app.php';

session_start();

try {
	$app = new Application($config);
	$app->run();
} catch (HttpException $e) {
	$e->run();
}
