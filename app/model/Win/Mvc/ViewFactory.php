<?php

namespace Win\Mvc;

use Win\Mvc\View;

/**
 * Fábrica de Views
 * 
 * Cria a view de acordo com a URL
 */
class ViewFactory {

	/**
	 * Cria uma view com base na página e parametros
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
