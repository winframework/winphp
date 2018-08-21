<?php

/**
 * Autoload Controller
 */
spl_autoload_register(function($className) {
	$file = BASE_PATH . '/../../www/app/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)):
		return require $file;
	endif;
});

/**
 * Autoload Controller Tests
 */
spl_autoload_register(function($className) {
	$file = BASE_PATH . '/app/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)):
		return require $file;
	endif;
});


/**
 * Autoload Model
 */
spl_autoload_register(function($className) {
	$file = BASE_PATH . '/../../www/app/model/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)):
		return require $file;
	endif;
});


/**
 * Autoload Model Tests
 */
spl_autoload_register(function($className) {
	$file = BASE_PATH . '/app/model/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)):
		return require $file;
	endif;
});
