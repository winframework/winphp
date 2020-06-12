<?php
/*
 * Para editar o código HTML procure em: app/templates/shared
 * @see app/templates/shared
 */

use Win\Application;
use Win\HttpException;

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
