<?php
/*
 * Para editar o código HTML procure em: templates/shared
 * @see templates/shared
 */

use App\Controllers\ErrorsController;
use Win\Application;
use Win\Common\Benchmark;
use Win\HttpException;
use Win\Services\Router;

define('BASE_PATH', __DIR__);
require 'app/autoload.php';
session_start();

$b = new Benchmark();
for ($i = 0; $i < 1; $i++) {
	try {
		$app = new Application();
		require '../../www/config/app.php';
		require 'config/routes.php';

		$app->run(...Router::instance()->getDestination());
	} catch (HttpException $e) {
		$app->run(ErrorsController::class, "_{$e->getCode()}", $e);
	} catch (Throwable $e) {
		$app->run(ErrorsController::class, "_500", $e);
	}
}
echo '<hr />';
echo $b->getTime();
echo '<hr />';
echo $b->getMemory();
