<?php

namespace Win\Utils;

use Win\Services\Router;

/**
 * Utilitário de Links de Navegação
 */
abstract class Link
{
	/**
	 * Usado para ativar Links (aceita múltiplos parâmetros)
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
