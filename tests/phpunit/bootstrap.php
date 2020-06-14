<?php

use Win\Application;

define('BASE_PATH', __DIR__ . '/../www');

include BASE_PATH . '/app/autoload.php';
include BASE_PATH . '/config/app.php';
include BASE_PATH . '/config/locale.php';

new Application();
