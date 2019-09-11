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
	 * Cria uma View com base nos parâmetros atuais
	 * @return View
	 */
	public static function create()
	{
		$app = Application::app();
		$params = $app->getParams();

		if ($params[0] == 'errors' && $params[1] == '404') {
			var_dump('ae');
			$view = new View('404');
		} else {
			$view = new View($params[0]);
			if (!$view->exists()) {
				$view = new View($params[0] . '/' . $params[1]);
			}
		}

		return $view;
	}
}
