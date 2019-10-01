<?php
/**
 * Autoload Base
 */
spl_autoload_register(function ($className) {
	$className = str_replace('App\\', 'app\\', $className);
	$file = BASE_PATH . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)) {
		return require $file;
	}
});

/**
 * Autoload Base Tests
 */
spl_autoload_register(function ($className) {
	$className = str_replace('App\\', 'app\\', $className);
	$file = BASE_PATH . '../../tests/unit/www/app/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)) {
		return require $file;
	}
});

/**
 * Autoload Vendor
 */
spl_autoload_register(function ($className) {
	$file = BASE_PATH . '/../../www/vendor/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)) {
		return require $file;
	}
});
