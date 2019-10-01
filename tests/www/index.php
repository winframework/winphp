<?php
/*
 * Para editar o código HTML procure em: /templates
 * @see /templates
 */

use Win\Application;
use Win\Response\ResponseException;

define('BASE_PATH', __DIR__);

require 'app/autoload.php';
require '../../www/config/app.php';
require 'config/routes.php';

session_start();

try {
	$app = new Application();
	$app->sendResponse();
} catch (ResponseException $e) {
	$e->sendResponse();
}
