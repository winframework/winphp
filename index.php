<?php

session_start();

require 'lib/autoload.php';
require 'app/config/app.php';
require 'app/config/routes.php';

$app = new Win\Mvc\Application($config);

/**
 * Para editar o código html do layout, procure em:
 * app/block/layout/
 */
$app->run();
