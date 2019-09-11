<?php

namespace Win\Mvc;

use Win\Formats\Str;

/**
 * Fábrica de Controllers
 *
 * Cria o Controller de acordo com a Página/Rota
 */
class ControllerFactory
{
	/**
	 * Cria um Controller com base nos parâmetros atuais
	 * @return Controller
	 */
	public static function create()
	{
		$class = static::getClassName();

		return class_exists($class) ? new $class() : new DefaultController();
	}

	/**
	 * Retorna nome de um Controller válido
	 * @return string
	 */
	protected static function getClassName()
	{
		$page = Application::app()->getParam(0);
		$controllerName = ucwords(str_replace('-', ' ', $page) . 'Controller');

		return 'controllers\\' . str_replace(' ', '', $controllerName);
	}
}
