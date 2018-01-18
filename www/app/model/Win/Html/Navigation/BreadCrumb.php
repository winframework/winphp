<?php

/**
 * BreadCrumbs
 * Cria breadcrumbs personalizados
 * Exemplo de um breadcrumb: - Página Inicial > Produto > Óculos de Sol
 *
 */

namespace Win\Html\Navigation;

use Win\Mvc\Application;

class BreadCrumb {

	private static $text;

	/**
	 * Melhora o BreadCrumb, adicionando Links se possível
	 * @param string $breadCrumb apenas texto
	 * @return string BreadCrumb com Links
	 */
	public static function createLink($breadCrumb) {
		$app = Application::app();

		$baseLink = explode('»', $breadCrumb, 2);
		if (count($baseLink) > 1) {
			if ($app->controller->getAction() != 'index' and trim($baseLink[0]) != 'Página Inicial') {
				return '<a href="' . $app->getPage() . '/">' . $baseLink[0] . '</a> » ' . $baseLink[1];
			} else {
				return '<a href="">' . $baseLink[0] . '</a> » ' . $baseLink[1];
			}
		} else {
			return $breadCrumb;
		}
	}

	public static function getLink() {
		return static::createLink(static::getText());
	}

	public static function getText() {
		return self::$text;
	}

	public static function setText($text) {
		self::$text = $text;
	}

}
