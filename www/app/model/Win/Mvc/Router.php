<?php

namespace Win\Mvc;

use Win\DesignPattern\Singleton;
use Win\Helper\Url;

/**
 * Rota de URL
 * 
 * Redireciona a requisição para um outro Controller.
 * Veja as rotas em "config/routes.php"
 */
class Router {

	use Singleton;

	/** @var string[] */
	private $routes = [];
	public static $file = '/app/config/routes.php';

	/**
	 * Se for atribuído algum valor indica que é uma rota personalizada
	 * @var mixed[]
	 */
	protected static $customUrl = [null, null];

	/**
	 * Retorna a nova URL
	 * @return mixed[]
	 */
	public function getCustomUrl() {
		return static::$customUrl;
	}

	/**
	 * Retorna TRUE se a URL foi personalizada
	 * @return boolean
	 */
	public function hasCustomUrl() {
		return (!is_null(static::$customUrl[0]));
	}

	/**
	 * Inicia o processo de URL personalizada
	 * retornando TRUE se alguma rota foi encontrada
	 * @return boolean
	 */
	public function run() {
		$this->load();
		if (count($this->routes)) {
			static::$customUrl = $this->createCustomUrl();
			return $this->hasCustomUrl();
		}
		return false;
	}

	/**
	 * Carrega o arquivo que contem as rotas
	 * @param string|null $routes
	 */
	public function load($routes = null) {
		if (!is_null($routes)) {
			$this->routes = $routes;
		}
		if (is_null($routes) && file_exists(BASE_PATH . static::$file)) {
			$this->routes = include BASE_PATH . static::$file;
		}
	}

	/**
	 * Percorre todas as rotas e retorna a nova URL
	 * @return string[] Nova URL [0 => controller, 1 => action]
	 */
	protected function createCustomUrl() {
		$search = ['', '$1', '$2', '$3', '$4', '$5', '$6', '$7', '$8', '$9', '$10'];
		$matches = [];
		foreach ($this->routes as $url => $route):
			$exists = preg_match('@' . Url::instance()->format($url) . '$@', Url::instance()->getUrl(), $matches) == 1;
			if ($exists):
				$route = str_replace($search, $matches, $route) . '/';
				return explode('/', $route);
			endif;
		endforeach;
		return [null, null];
	}

	/**
	 * Cria um Controller de acordo com a nova URL
	 * @return Controller|DefaultController
	 */
	public function createController() {
		return ControllerFactory::create(static::$customUrl[0], static::$customUrl[1]);
	}

}
