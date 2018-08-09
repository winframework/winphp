<?php

namespace Win\Html\Seo;

use Win\Mvc\Application;

/**
 * Auxilia a criar o titulo otimizado para SEO
 */
class Title {

	public static $maxLength = 70;
	public static $prefix = '';
	public static $sufix = '';

	/**
	 * Retorna o título com o nome da aplicação no final
	 * Mantendo o máximo de caracteres
	 * @param string $title
	 * @return string
	 */
	public static function otimize($title) {
		$maxLenght = static::$maxLength - strLength(static::$prefix) - strLength(static::$sufix) - 3;
		return static::$prefix . strTruncate($title, $maxLenght, true) . static::$sufix;
	}

	/**
	 * Define o título, otimizando
	 * @param string $title
	 */
	public static function setTitle($title) {
		Application::app()->controller->setTitle(static::otimize($title));
	}

}
