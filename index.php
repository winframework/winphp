<?php

/**
 * Para editar o código html do layout, procure em:
 * app/block/layout/
 */
define('BASE_PATH', __DIR__);

require 'lib/autoload.php';
require 'lib/functions/strings.php';
require 'app/config/app.php';
require 'app/config/routes.php';
require 'app/config/database.php';

session_start();
$app = new Win\Mvc\Application($config);
$db = new Win\Connection\Mysql($db);

$app->run();
