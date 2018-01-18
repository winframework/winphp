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

session_start();
$app = new Win\Mvc\Application($config);

$app->run();
