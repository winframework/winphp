<?php
/*
 * Para editar o código HTML procure em: templates/shared
 * @see templates/shared
 */

use Win\Application;
use Win\Common\Benchmark;
use Win\HttpException;

define('BASE_PATH', __DIR__);

require 'app/autoload.php';
require '../../www/config/app.php';
require 'config/locale.php';
require 'config/routes.php';

session_start();

$b = new Benchmark();
for ($i=0; $i < 1; $i++) { 
	# code...
	try {
		$app = new Application();
		$app->run();
	} catch (HttpException $e) {
		$e->run();
	}
}
echo '<hr />';
echo $b->getTime();