<?php

namespace Win\Mvc;

use Win\Formats\Str;
use Win\Request\Url;
use Win\Singleton\SingletonTrait;

/**
 * Rota de URL
 *
 * Redireciona a requisição para um outro Controller.
 * @see "/app/config/routes.php"
 */
class Router
{
	use SingletonTrait;


	/** @var string[] */
	private $routes = [];

	/**
	 * Define as rotas
	 * @param string[] $routes
	 */
	public function setRoutes($routes = [])
	{
		$this->routes = $routes;
	}

	/**
	 * Percorre todas as rotas e retorna o alvo da rota
	 * @return string[] Destino ["ControllerName", ["actionName"]
	 */
	public function getTarget()
	{
		$url = Url::instance();
		// $search = ['', '$1', '$2', '$3', '$4', '$5', '$6', '$7', '$8', '$9', '$10'];
		$matches = [];
		foreach ($this->routes as $source => $destination) {
			$pattern = '@^' . $url->format($source) . '$@';
			$exists = 1 == preg_match($pattern, $url->getUrl(), $matches);
			if ($exists) {
				return explode('/', $destination);
				// return str_replace($search, $matches, $destination) . '/';
			}
		}

		return static::autoTarget();
	}

	/**
	 * Retorna um Controller/action automaticamente com base na URL atual
	 * @return string[]
	 */
	public static function autoTarget()
	{
		$segments = Url::instance()->getSegments() + Url::HOME;

		return [
			Str::camel($segments[0]) . 'Controller',
			Str::lowerCamel($segments[1]),
		];
	}
}
