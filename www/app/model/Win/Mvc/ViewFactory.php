<?php

namespace Win\Mvc;

use Win\Mvc\View;

/**
 * Fábrica de Views
 * 
 * Cria a View de acordo com a URL
 */
class ViewFactory {

	/**
	 * Cria uma View com base na página e parâmetros
	 * @param string $page
	 * @param mixed[] $paramList
	 * @return View
	 */
	public static function create($page, $paramList = []) {
		$view = new View($page);
		if (!$view->exists()):
			$view = new View(implode('/', $paramList));
		endif;
		return $view;
	}

}
