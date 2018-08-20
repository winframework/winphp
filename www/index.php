<?php

/**
 * Para editar o código html do layout, procure em:
 * app/block/layout/
 */
require 'lib/autoload.php';
require 'lib/functions/strings.php';
require 'app/config/app.php';

session_start();
$app = new Win\Mvc\Application($config);

$app->run();
