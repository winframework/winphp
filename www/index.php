<?php
/*
 * Para editar o código HTML procure em: templates/shared
 * @see templates/shared
 */

use App\Controllers\ErrorsController;
use Win\Application;
use Win\HttpException;
use Win\Services\Router;

define('BASE_PATH', __DIR__);
require 'app/autoload.php';
require 'config/app.php';
require 'config/routes.php';

$app = new Application();

try {
	try {
		$app->run(...Router::instance()->getDestination());
	} catch (HttpException $e) {
		$app->run(ErrorsController::class, "_{$e->getCode()}", $e);
	}
} catch (Throwable $e) {
	$app->run(ErrorsController::class, '_500', $e);
}
