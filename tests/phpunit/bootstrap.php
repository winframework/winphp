<?php

use Win\Application;

define('BASE_PATH', __DIR__ . '/../www');

include BASE_PATH . '/app/autoload.php';
include BASE_PATH . '/../../www/config/app.php';

new Application();
