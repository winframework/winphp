<?php

namespace Win\Mvc;

use Win\Request\Url;

/**
 * Rota de URL
 *
 * Redireciona a requisição para um "Controller/action".
 * @see "/app/config/routes.php"
 */
class Router
{
	/** @var string[] */
	public static $routes = [];

	/**
	 * Percorre todas as rotas e retorna o destino da rota
	 * @return string[] Destino ["ControllerName", ["actionName"]
	 */
	public static function getDestination()
	{
		$url = Url::instance();
		// $search = ['', '$1', '$2', '$3', '$4', '$5', '$6', '$7', '$8', '$9', '$10'];
		$matches = [];
		foreach (static::$routes as $request => $destination) {
			$pattern = '@^' . $url->format($request) . '$@';
			$exists = 1 == preg_match($pattern, $url->getUrl(), $matches);
			if ($exists) {
				return explode('/', $destination);
				// return str_replace($search, $matches, $destination) . '/';
			}
		}

		return Application::app()->page404();
	}
}
