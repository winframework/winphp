<?php

use Win\File\Image\Thumb;

define('BASE_PATH', __DIR__ . '/../..');
include BASE_PATH . '/lib/autoload.php';

$thumb = new Thumb();

$thumb->setCache('data/cache');
$thumb->setDefault('lib/thumb/default.png', 80);
$thumb->config($_GET['config']);

$thumb->show();
