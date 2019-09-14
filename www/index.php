<?php
/*
 * Para editar o código HTML procure em: app/templates
 * @see app/templates
 */

use Win\Calendar\Timer;
use Win\Mvc\Application;
use Win\Mvc\HttpException;

define('BASE_PATH', __DIR__);

require 'libs/autoload.php';
require 'app/config/app.php';
require 'app/config/routes.php';

session_start();

$t = new Timer();

for ($i = 0; $i < 1; ++$i) {
	try {
		$app = new Application();
		$app->run();
	} catch (HttpException $e) {
		$e->run();
	}
}

echo $t->time();
