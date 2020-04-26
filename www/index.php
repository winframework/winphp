<?php
/*
 * Para editar o código HTML procure em: /app/templates
 * @see /app/templates
 */

use Win\Application;
use Win\Request\HttpException;

define('BASE_PATH', __DIR__);

require 'app/autoload.php';
require 'app/config/app.php';
require 'app/config/routes.php';

session_start();

try {
	$app = new Application();
	$app->run();
} catch (HttpException $e) {
	$e->run();
}
