<?php

/*
 * Para editar o código HTML do layout, procure em:
 * app/templates/
 */

use Win\Mvc\Application;
use Win\Mvc\HttpException;

define('BASE_PATH', __DIR__);

require 'autoload.php';
require '../../www/app/config/app.php';

session_start();

try {
	$app = new Application($config);
	$app->run();
} catch (HttpException $e) {
	$e->run();
}
