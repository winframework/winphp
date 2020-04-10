<?php

namespace Win\Response;

use Exception;
use Win\Application;
use Win\Common\Template;

class ResponseFactory
{
	/**
	 * Envia uma resposta baseada no destino [Controller, action, [...args]]
	 * @param array $destination
	 */
	public static function send($destination)
	{
		$class = $destination[0];
		$action = rtrim($destination[1], '@') ?? '';
		$args = $destination[2] ?? [];

		if (!class_exists($class)) {
			throw new ResponseException("Controller '{$class}' not found", 404);
		}

		$controller = new $class();
		$controller->app = Application::app();
		$controller->app->controller = $controller;
		if (!method_exists($controller, $action)) {
			throw new ResponseException("Action '{$action}' not found in '{$class}'", 404);
		}

		$response = $controller->$action(...$args);
		echo ($response instanceof Response) ? $response->respond() :  $response;
	}
}
