<?php

namespace Win\Core\Request;

use Win\Core\Response\ResponseException;

/**
 * Rota de URL
 *
 * Redireciona a requisição para um "Controller@action".
 * @see "/app/config/routes.php"
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
	public static function routes($namespace, $routes)
	{
		foreach ($routes as $request => $destination) {
			static::$routes[$request] = $namespace . $destination;
		}
	}

	/**
	 * Percorre todas as rotas e retorna o destino final
	 * @return string[] Destino
	 * @example return [Controller, action, [..$args]]
	 */
	public static function getDestination()
	{
		$url = Url::instance();
		$matches = [];

		foreach (static::$routes as $request => $destination) {
			$pattern = '@^' . $url->format($request) . '$@';
			$match = 1 == preg_match($pattern, $url->getUrl(), $matches);
			if ($match) {
				$args = array_splice($matches, 1);
				$target = array_pad(explode('@', $destination), 2, '');
				$target[] = $args;

				return $target;
			}
		}

		throw new ResponseException('Route not found', 404);
	}
}
