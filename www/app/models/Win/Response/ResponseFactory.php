<?php

namespace Win\Response;

use Win\Mvc\Application;

class ResponseFactory
{
	/**
	 * Cria uma resposta baseada no destino [Controller, action, [...args]]
	 * @param array $destination
	 */
	public static function create($destination)
	{
		$app = Application::app();
		$controllerClass = 'controllers\\' . $destination[0];
		$action = rtrim($destination[1], '@') ?? '';
		$args = $destination[2] ?? [];

		if (class_exists($controllerClass)) {
			$app->controller = new $controllerClass();
			$app->controller->app = $app;

			if (method_exists($app->controller, $action)) {
				return $app->controller->$action(...$args);
			} else {
				$msg = "Action '{$action}' not found in '{$destination[0]}'";
				throw new ResponseException($msg, 404);
			}
		} else {
			throw new ResponseException("Controller '{$destination[0]}' not found", 404);
		}
	}
}
