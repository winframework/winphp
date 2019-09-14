<?php
/*
 * Para editar o código HTML procure em: app/templates
 * @see app/templates
 */

use Win\Mvc\Application;

define('BASE_PATH', __DIR__);

require 'libs/autoload.php';
require 'app/config/app.php';
require 'app/config/routes.php';

session_start();

try {
	$app = new Application();
	$app->sendResponse();
} catch (ResponseException $e) {
	$e->sendResponse();
}
