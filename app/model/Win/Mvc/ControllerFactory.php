<?php

namespace Win\Mvc;

use Win\Mvc\DefaultController;

/**
 * Fábrica de Controllers
 * 
 * Cria o controller de acordo com a Rota
 */
class ControllerFactory {

	/**
	 * Cria um controller com base na página
	 * @param string $page
	 * @param string $action
	 * @return Controller
	 */
	public static function create($page, $action = null) {
		$class = static::getControllerClass($page);
		if (class_exists($class)) {
			return new $class($action);
		} elseif (Route::instance()->hasCustomUrl() xor ! is_null($action)) {
			return Route::instance()->createController();
		}
		return new DefaultController();
	}

	/** @return string */
	protected static function getControllerClass($page) {
		return 'controller\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $page) . 'Controller'));
	}

}
