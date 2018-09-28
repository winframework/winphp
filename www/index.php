<?php

use Win\Mvc\Application;
use Win\Mvc\HttpException;

/**
 * Para editar o código HTML do layout, procure em:
 * app/block/layout/
 */
define('BASE_PATH', __DIR__);

require 'lib/autoload.php';
require 'lib/functions/strings.php';
require 'app/config/app.php';

session_start();

try {
	$app = new Application($config);
	$app->run();
} catch (HttpException $e) {
	$e->run();
}

