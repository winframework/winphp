<?php

/**
 * Autoload App
 */
spl_autoload_register(function ($className) {
	$className = str_replace('App\\', 'app\\', $className);
	$file = BASE_PATH . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)) {
		return require $file;
	}
});

/**
 * Autoload Vendor
 */
spl_autoload_register(function ($className) {
	$file = BASE_PATH . '/vendor/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)) {
		return require $file;
	}
});
