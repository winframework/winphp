<?php

namespace Win\Common\Utils;

use Win\Application;
use Win\Request\Url;

/**
 * Auxilia a criar Links de Navegação
 */
class Link
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
		$current = implode('/', Url::$segments);

		foreach ($links as $link) {
			if (0 === strpos(Url::format($current), Url::format($link))) {
				return 'active';
			}
		}

		return '';
	}
}
