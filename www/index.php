<?php
/*
 * Para editar o código HTML procure em: templates/shared
 * @see templates/shared
 */

use App\Controllers\ErrorsController;
use Win\Application;
use Win\HttpException;
use Win\Request\Router;

define('BASE_PATH', __DIR__);

require 'app/autoload.php';
require 'config/app.php';
require 'config/routes.php';

try {
	$app = new Application();
	$app->run(...Router::getDestination());
} catch (HttpException $e) {
	$app->run(ErrorsController::class, "error{$e->getCode()}", [$e]);
}
