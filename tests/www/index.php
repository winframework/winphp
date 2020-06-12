<?php
/*
 * Para editar o código HTML procure em: app/templates/shared
 * @see app/templates/shared
 */

use App\Repositories\PageOrm;
use Win\Application;
use Win\Common\Benchmark;
use Win\HttpException;

define('BASE_PATH', __DIR__);

require 'app/autoload.php';
require '../../www/app/config/app.php';
require 'app/config/locale.php';
require 'app/config/routes.php';

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