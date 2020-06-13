<?php
/*
 * Para editar o código HTML procure em: templates/shared
 * @see templates/shared
 */

use Win\Application;
use Win\HttpException;

define('BASE_PATH', __DIR__);

require 'app/autoload.php';
require 'config/app.php';
require 'config/routes.php';

session_start();

try {
	$app = new Application();
	$app->run();
} catch (HttpException $e) {
	$e->run();
}
