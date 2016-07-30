<?php

namespace Win\Html;

use Win\Mvc\Application;
use Win\Helper\Url;

/**
 * Auxilia com funcionalidades em Menus de Navegação
 *
 */
class Menu {

	/**
	 * Usado para ativar links
	 *
	 * Retorna 'active' se o link informado for a página atual
	 * ou se o link for idêntico ao início da Url
	 * @param string $link href do link/botão
	 * @return string 'active'|''
	 */
	public static function active($link) {
		$app = Application::app();
		if ($link === $app->getPage() || strpos($app->getUrl(), Url::instance()->format($link)) === 0) {
			return 'active';
		}
		return '';
	}

	/**
	 * Usado para ativar multiplos links
	 *
	 * Semelhante ao metodo active(), mas este aceita arrays
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
