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
	 * Cria um Controller com base na página/rota
	 * @param string $page
	 * @param string|null $action
	 * @return Controller
	 */
	public static function create($page, $action = null)
	{
		$class = static::formatClass($page);
		if (class_exists($class)) {
			$action = static::formatAction($action);

			return new $class($action);
		}

		return new DefaultController();
	}

	/**
	 * Retorna nome de um Action válido
	 * @param string $string
	 * @return string
	 */
	protected static function formatAction($string)
	{
		if (empty($string)) {
			$string = Application::app()->getParam(1);
		}

		return Str::lowerCamel($string);
	}

	/**
	 * Retorna nome de um Controller válido
	 * @param string $page
	 * @return string
	 */
	protected static function formatClass($page)
	{
		$controllerName = ucwords(str_replace('-', ' ', $page) . 'Controller');

		return 'controllers\\' . str_replace(' ', '', $controllerName);
	}
}