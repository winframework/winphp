<?php

namespace Win\Mvc;

use Win\Request\Url;

/**
 * Rota de URL
 *
 * Redireciona a requisição para um "Controller@action".
 * @see "/app/config/routes.php"
 */
class Router
{
	/** @var string[] */
	public static $routes = [];

	/**
	 * Percorre todas as rotas e retorna o destino da rota
	 * @return string[] Destino
	 * @example return ["ControllerName", ["actionName", [..$args]]
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
				$target = explode('@', $destination);
				$target[] = $args;

				return $target;
			}
		}

		return Application::app()->page404();
	}
}
