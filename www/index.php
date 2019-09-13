<?php
/*
 * Para editar o código HTML procure em: app/templates
 * @see app/templates
 */
use Win\Mvc\Application;
use Win\Mvc\HttpException;

define('BASE_PATH', __DIR__);

require 'libs/autoload.php';
require 'app/config/app.php';

session_start();

try {
	$app = new Application();
	$app->run();
} catch (HttpException $e) {
	$e->run();
}
