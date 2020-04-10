<?php
/*
 * Para editar o código HTML procure em: /app/templates
 * @see /app/templates
 */

use Win\Application;
use Win\Common\Benchmark;
use Win\Response\ResponseException;

define('BASE_PATH', __DIR__);

require 'app/autoload.php';
require '../../www/app/config/app.php';
require 'app/config/routes.php';

session_start();

$b = new Benchmark();
for ($i=0; $i < 100; $i++) { 
	# code...
	try {
		$app = new Application();
		$app->sendResponse();
	} catch (ResponseException $e) {
		$e->sendResponse();
	}
}

echo $b->getTime();