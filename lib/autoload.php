<?php

/**
 * Autoload Controller
 */
spl_autoload_register(function ($className) {
	$controllerFile = BASE_PATH . '/app/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($controllerFile)):
		return require $controllerFile;
	endif;
});

/**
 * Autoload Model
 */
spl_autoload_register(function ($className) {
	$modelFile = BASE_PATH . '/app/model/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($modelFile)):
		return require $modelFile;
	endif;
});
