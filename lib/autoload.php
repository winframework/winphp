<?php

/**
 * Adiciona o arquivo de classe automaticamente quando a classe é chamada
 * @param string $className
 */
function __autoload($className) {
	$basePath = dirname(realpath(__FILE__)) . '/../';

	/* Models */
	$file = $basePath . 'app/model/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)):
		return require $file;
	endif;

	/* Controllers */
	$file = $basePath . 'app/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	if (file_exists($file)):
		return require $file;
	endif;
}
