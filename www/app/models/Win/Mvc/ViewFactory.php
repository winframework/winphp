<?php

namespace Win\Mvc;

use Win\Request\Url;

/**
 * Fábrica de View
 *
 * Cria a View de acordo com a URL
 */
class ViewFactory
{
	/**
	 * Cria uma View automática com base na URL atual
	 * @return View
	 */
	public static function create()
	{
		$segments = Url::instance()->getSegments();
		$view = new View($segments[0]);
		if (!$view->exists()) {
			$view = new View($segments[0] . '/' . $segments[1]);
		}

		return $view;
	}
}
