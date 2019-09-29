<?php

namespace Win\Core\Common\Utils;

use Win\Core\Request\Url;

/**
 * Auxilia a criar Menus de Navegação
 */
class Menu
{
	/**
	 * Usado para ativar Links (aceita array)
	 *
	 * Retorna 'active' se o link for idêntico ao início da URL atual
	 * @param string ...$links
	 * @return string
	 */
	public static function active(...$links)
	{
		$url = Url::instance();
		$current = implode('/', $url->getSegments());

		foreach ($links as $link) {
			if (0 === strpos($url->format($current), $url->format($link))) {
				return 'active';
			}
		}

		return '';
	}
}
