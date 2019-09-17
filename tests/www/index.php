<?php
/*
 * Para editar o código HTML procure em: app/templates
 * @see app/templates/
 */

use Win\Calendar\Timer;
use Win\Mvc\Application;
use Win\Response\ResponseException;

define('BASE_PATH', __DIR__);

require 'autoload.php';
require '../../www/app/config/app.php';
require 'app/config/routes.php';

session_start();


try {
	$app = new Application();
	$app->sendResponse();
} catch (ResponseException $e) {
	$e->sendResponse();
}
