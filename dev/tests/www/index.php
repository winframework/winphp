<?php

/**
 * Para editar o código HTML do layout, procure em:
 * app/block/layout/
 */
define('BASE_PATH', __DIR__);

require 'autoload.php';
require '../../../www/lib/functions/strings.php';
require '../../../www/app/config/app.php';

session_start();
$app = new Win\Mvc\Application($config);

$app->run();
