<?php
/*
 * Para editar o código HTML do layout, procure em layout/
 * @see app/block/layout/
 */
use Win\Mvc\Application;
use Win\Mvc\HttpException;

define('BASE_PATH', __DIR__);

require 'lib/autoload.php';
require 'app/config/app.php';

session_start();

try {
	$app = new Application($config);
	$app->run();
} catch (HttpException $e) {
	$e->run();
}
