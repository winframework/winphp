<?php

namespace Win\Mvc;

use Win\Helper\Url;
use Win\DesignPattern\Singleton;

/**
 * Rota de URL
 * 
 * Envia a requisição para o controller correto.
 * Veja as rotas em config/routes.php
 */
class Route extends Singleton {

	/**
	 * Define se as rotas já foram analisadas
	 *
	 * Isso faz com que as rotas sejam analisadas apenas uma vez
	 * @var boolean
	 */
	protected static $analyzed = false;

	/**
	 * Se for atribuido algum valor indica que é uma rota personalizada
	 * @var mixed[]
	 */
	protected static $customUrl = [null, null];

	/**
	 * Retorna a Url que foi modificada pelas rotas
	 * @return mixed[]
	 */
	public function getCustomUrl() {
		return static::$customUrl;
	}

	/**
	 * Retorna TRUE se a URL foi customizada
	 * @return boolean
	 */
	public function hasCustomUrl() {
		return (!is_null(static::$customUrl[0]));
	}

	/**
	 * Cria um controller manualmente
	 * 
	 * Executado apenas se não possui rota automática
	 * @return Controller|DefaultController
	 */
	public function createController() {
		$routeList = (array) Application::app()->getConfig('route', []);

		if (!static::$analyzed):
			static::$customUrl = static::createCustomUrl($routeList);
			static::$analyzed = true;
		endif;

		return ControllerFactory::create(static::$customUrl[0], static::$customUrl[1]);
	}

	/**
	 * Percorre todas as rotas e retorna a Url personalizada
	 * 
	 * Se encontrar alguma rota, retorna a Url personalizada em Expressão regular
	 * @param string[] $routeList
	 * @return string[]
	 */
	public function createCustomUrl($routeList) {
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

}
