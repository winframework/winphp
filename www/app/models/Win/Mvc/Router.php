<?php

namespace Win\Mvc;

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
	public static $file = '/app/config/routes.php';

	/**
	 * Se for atribuído algum valor indica que é uma rota personalizada
	 * @var mixed[]
	 */
	protected static $customUrl = [null, null];

	/**
	 * Inicia o processo de URL personalizada
	 * @return bool
	 */
	public function getParams()
	{
		$this->load();

		return explode('/', $this->getDestination());
	}

	/**
	 * Carrega o arquivo que contem as rotas
	 * @param string[] $routes
	 */
	public function load($routes = [])
	{
		$this->routes = $routes;
		if (empty($routes) && file_exists(BASE_PATH . static::$file)) {
			$this->routes = include BASE_PATH . static::$file;
		}
	}

	/**
	 * Percorre todas as rotas e retorna a nova URL
	 * @return string[] Nova URL [0 => controller, 1 => action]
	 */
	protected function getDestination()
	{
		// $search = ['', '$1', '$2', '$3', '$4', '$5', '$6', '$7', '$8', '$9', '$10'];
		$matches = [];
		foreach ($this->routes as $source => $destination) {
			$pattern = '@' . Url::instance()->format($source) . '$@';
			$exists = 1 == preg_match($pattern, Url::instance()->getUrl(), $matches);
			if ($exists) {
				return $destination;
				// return str_replace($search, $matches, $destination) . '/';
			}
		}

		return Url::instance()->getUrl();
	}
}
