<?php

namespace Win\Mvc;

use Win\Helper\Url;

/**
 * Rota de URL
 * 
 * Redireicona a requisição para um outro controller.
 * Veja as rotas em config/routes.php
 */
class Route {

	use \Win\DesignPattern\Singleton;

	/**
	 * Se for atribuido algum valor indica que é uma rota personalizada
	 * @var mixed[]
	 */
	protected static $customUrl = [null, null];

	/**
	 * Retorna a nova Url
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
	 * Inicia o processo de Url personalizada
	 * retornando TRUE se alguma rota foi encontrada
	 * @return boolean
	 */
	public function run() {
		if (!is_null(Application::app()->getConfig('route', null))) {
			static::$customUrl = static::createCustomUrl();
			return $this->hasCustomUrl();
		}
		return false;
	}

	/**
	 * Percorre todas as rotas e retorna a nova Url
	 * 
	 * @return string[] Nova Url [0 => controller, 1 => action]
	 */
	protected function createCustomUrl() {
		$routeList = (array) Application::app()->getConfig('route', []);
		$search = ['', '$1', '$2', '$3', '$4', '$5', '$6', '$7', '$8', '$9', '$10'];
		$matches = [];
		foreach ($routeList as $url => $route):
			$exists = preg_match('@' . Url::instance()->format($url) . '$@', Url::instance()->getUrl(), $matches) == 1;
			if ($exists):
				$route = str_replace($search, $matches, $route) . '/';
				return explode('/', $route);
			endif;
		endforeach;
		return [null, null];
	}

	/**
	 * Cria um controller de acordo com a nova Url
	 *
	 * @return Controller|DefaultController
	 */
	public function createController() {
		return ControllerFactory::create(static::$customUrl[0], static::$customUrl[1]);
	}

}
