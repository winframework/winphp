<?php

namespace Win\Html\Seo;

use Win\Mvc\Application;

/**
 * Auxilia a criar o titulo otimizado para SEO
 */
class Title {

	protected static $MAX_LENGTH = 70;
	protected static $SEPARATOR = ' | ';

	/**
	 * Retorna o titulo com o nome da aplicação no final
	 * Mantendo o maximo de caracteres
	 * @param string $title
	 * @return string
	 */
	public static function otimize($title) {
		$name = Application::app()->getName();
		$maxLenght = static::$MAX_LENGTH - strLength($name) - strLength(static::$SEPARATOR) - 3;
		return strTruncate($title, $maxLenght, true) . static::$SEPARATOR . $name;
	}

	/**
	 * Define o titulo, otimizando
	 * @param string $title
	 */
	public static function setTitle($title) {
		Application::app()->controller->setTitle(static::otimize($title));
	}

}
