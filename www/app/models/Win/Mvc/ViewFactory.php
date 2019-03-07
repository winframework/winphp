<?php

namespace Win\Mvc;

/**
 * Fábrica de View
 *
 * Cria a View de acordo com a URL
 */
class ViewFactory
{
	/**
	 * Cria uma View com base na página e parâmetros
	 * @param string $page
	 * @param string $action
	 * @return View
	 */
	public static function create($page, $action = null)
	{
		if (HttpException::isErrorCode($page)) {
			$view = new View('');
		} else {
			$view = new View($page);
			if (!$view->exists()) {
				$view = new View($page . '/' . $action);
			}
		}

		return $view;
	}
}
