<?php

namespace Win\Mvc;

/**
 * Fábrica de Controllers
 *
 * Cria o Controller de acordo com a Página/Rota
 */
class ControllerFactory
{
	/**
	 * Retorna o Controller que é o alvo da rota
	 * @return Controller
	 */
	public static function create($target)
	{
		$class = 'controllers\\' . $target[0];

		if (class_exists($class)) {
			$controller = new $class();
			$controller->action = $target[1];
			$controller->app = Application::app();
		} else {
			Application::app()->page404();
		}

		return $controller;
	}
}
