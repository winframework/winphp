<?php

namespace Win\Request;

use Win\Application;
use Win\HttpException;
use Win\Response\Response;

/**
 * Rota de URL
 *
 * Redireciona a requisição para um "Controller@action".
 * @see "app/config/routes.php"
 */
class Router
{
	/** @var string[] */
	protected static $routes = [];

	/**
	 * Adiciona as rotas
	 * @param string $namespace
	 * @param string[] $routes
	 */
	public static function addRoutes($namespace, $routes)
	{
		foreach ($routes as $request => $destination) {
			static::$routes[$request] = $namespace . $destination;
		}
	}

	/**
	 * Percorre todas as rotas e retorna o destino final
	 * @return array Destino
	 * @example return [Controller, action, [..$args]]
	 */
	public static function getDestination()
	{
		$url = Url::instance();
		$matches = [];

		foreach (static::$routes as $request => $destination) {
			$pattern = '@^' . $url->format($request) . '$@';
			$match = preg_match($pattern, $url->getUrl(), $matches);
			if ($match) {
				$target = array_pad(explode('@', $destination), 2, '');
				$target[] = (array) array_splice($matches, 1);

				return $target;
			}
		}

		throw new HttpException('Route not found', 404);
	}

	/**
	 * Processa e envia uma resposta baseada no destino 
	 * @param array $destination
	 * @example process([Controller, action, [...args]])
	 */
	public static function process($destination)
	{
		$class = $destination[0];
		$action = $destination[1] ?? '';
		$args = $destination[2] ?? [];

		if (!class_exists($class)) {
			throw new HttpException("Controller '{$class}' not found", 404);
		}

		$controller = new $class(Application::app());
		$controller->app = Application::app();
		$controller->app->controller = $controller;

		if (!method_exists($controller, $action)) {
			throw new HttpException("Action '{$action}' not found in '{$class}'", 404);
		}

		$controller->init();
		$response = $controller->$action(...$args);
		echo ($response instanceof Response) ? $response->respond() : $response;
	}
}
