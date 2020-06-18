<?php

namespace Win\Request;

use Win\HttpException;

/**
 * Rota de URL
 *
 * Define o Controller@action a ser executado baseado na URL
 * @see "config/routes.php"
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
	public static function add($namespace, $routes)
	{
		foreach ($routes as $request => $destination) {
			static::$routes[$request] = $namespace . $destination;
		}
	}

	/**
	 * Percorre todas as rotas e retorna o destino final
	 * @return array Destino
	 * @example return [Controller, action, ...$args]
	 */
	public static function getDestination()
	{
		$url = Url::format(Url::$path);
		$matches = [];

		foreach (static::$routes as $request => $destination) {
			$pattern = '@^' . Url::format($request) . '$@';
			$match = preg_match($pattern, $url, $matches);
			if ($match) {
				return [...explode('@', $destination), ...array_splice($matches, 1)];
			}
		}

		throw new HttpException('Route not found', 404);
	}
}
