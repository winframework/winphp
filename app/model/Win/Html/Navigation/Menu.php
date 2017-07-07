<?php

namespace Win\Html\Navigation;

use Win\Mvc\Application;
use Win\Helper\Url;

/**
 * Auxilia com funcionalidades em Menus de Navegação
 *
 */
class Menu {

	/**
	 * Usado para ativar links (aceita arrays)
	 *
	 * Retorna 'active' se o link informado for a página atual
	 * ou se o link for idêntico ao início da Url
	 * @param string|string[] $link href do link/botão
	 * @return string 'active'|''
	 */
	public static function active($link) {
		if (is_array($link)) {
			return static::multiActive($link);
		}
		$app = Application::app();
		if ($link === $app->getPage() || strpos($app->getUrl(), Url::instance()->format($link)) === 0) {
			return 'active';
		}
		return '';
	}

	/**
	 * Usado para ativar multiplos links
	 * @param string[] $linkList
	 * @return string 'active'|''
	 */
	public static function multiActive($linkList) {
		foreach ($linkList as $link) {
			if (static::active($link) === 'active'):
				return 'active';
			endif;
		}
		return '';
	}

}
