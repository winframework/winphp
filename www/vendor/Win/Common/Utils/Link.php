<?php

namespace Win\Common\Utils;

use Win\Services\Router;

/**
 * Auxilia a criar Links de Navegação
 */
abstract class Link
{
	/**
	 * Usado para ativar Links (aceita array)
	 *
	 * Retorna 'active' se o link for idêntico ao início da URL atual
	 * @param string[] $links
	 * @return string
	 */
	public static function active(...$links)
	{
		$router = Router::instance();
		$current = implode('/', $router->segments);

		foreach ($links as $link) {
			if (0 === strpos($router->format($current), $router->format($link))) {
				return 'active';
			}
		}

		return '';
	}
}
