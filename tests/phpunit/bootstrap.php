<?php

use Win\Application;

define('BASE_PATH', __DIR__ . '/../www');

include BASE_PATH . '/app/autoload.php';

define('APP_NAME', 'WinPHP');

new Application();
