<?php

namespace Win\Html\Seo;

use Win\Format\Str;
use Win\Mvc\Application;

/**
 * Auxilia a criar o titulo otimizado para SEO
 */
class Title {

	public static $prefix = '';
	public static $sufix = '';

	/**
	 * Retorna o título com o nome da aplicação no final
	 * Mantendo o máximo de caracteres
	 * @param string $title
	 * @return string
	 */
	public static function otimize($title, $maxLength = 70) {
		$maxLenght = $maxLength - Str::length(static::$prefix) - Str::length(static::$sufix);
		return static::$prefix . Str::truncate($title, $maxLenght, false) . static::$sufix;
	}

	/**
	 * Define o título, otimizando
	 * @param string $title
	 */
	public static function setTitleOtimized($title) {
		Application::app()->controller->setTitle(static::otimize($title));
	}

}
