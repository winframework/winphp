<?php

namespace Win\Response;

use Exception;
use Win\Application;

class ResponseFactory
{
	/**
	 * Cria uma resposta baseada no destino [Controller, action, [...args]]
	 * @param array $destination
	 */
	public static function create($destination)
	{
		$app = Application::app();
		$controllerClass = $destination[0];
		$action = rtrim($destination[1], '@') ?? '';
		$args = $destination[2] ?? [];

		if (class_exists($controllerClass)) {
			$app->controller = new $controllerClass();
			$app->controller->app = $app;

			if (method_exists($app->controller, $action)) {
				return $app->controller->$action(...$args);
			} else {
				$msg = "Action '{$action}' not found in '{$controllerClass}'";
				throw new ResponseException($msg, 404);
			}
		} else {
			throw new ResponseException("Controller '{$controllerClass}' not found", 404);
		}
	}
}
