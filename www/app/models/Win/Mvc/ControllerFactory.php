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
	public static function create()
	{
		$target = Router::instance()->getTarget();
		$class = 'controllers\\' . $target[0];

		$controller = class_exists($class) ? new $class() : new DefaultController();
		$controller->action = $target[1];

		return $controller;
	}
}
