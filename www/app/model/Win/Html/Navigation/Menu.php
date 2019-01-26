<?php

namespace Win\Html\Navigation;

use Win\Request\Url;
use Win\Mvc\Application;

/**
 * Auxilia a criar Menus de Navegação
 *
 */
class Menu {

	/**
	 * Usado para ativar Links (aceita array)
	 *
	 * Retorna 'active' se o link informado for a página atual
	 * ou se o link for idêntico ao início da URL
	 * @param string|string[] $link 
	 * @return string
	 */
	public static function active($link) {
		if (is_array($link)) {
			return static::multiActive($link);
		}
		$app = Application::app();
		if ($link === $app->getPage()) {
			return 'active';
		}
		$url = Url::instance();
		if (strpos($url->getUrl(), $url->format($link)) === 0) {
			return 'active';
		}
		return '';
	}

	/**
	 * Usado para ativar múltiplos Links
	 * @param string[]
	 * @return string
	 */
	public static function multiActive($links) {
		foreach ($links as $link) {
			if (static::active($link) === 'active') {
				return 'active';
			}
		}
		return '';
	}

}
