<?php

namespace Win\Common\Utils;

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
