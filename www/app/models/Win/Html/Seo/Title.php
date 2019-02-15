<?php

namespace Win\Html\Seo;

use Win\Format\Type\Str;
use Win\Mvc\Application;

/**
 * Auxilia a criar o título otimizado para SEO
 */
class Title {

	public static $prefix = '';
	public static $suffix = '';

	/**
	 * Retorna o título com o nome da aplicação no final
	 * Mantendo o máximo de caracteres
	 * @param string $title
	 * @return string
	 */
	public static function otimize($title, $maxLength = 70) {
		$staticLength = Str::length(static::$prefix) + Str::length(static::$suffix);
		$maxLength = $maxLength - $staticLength;
		return static::$prefix . Str::truncate($title, $maxLength) . static::$suffix;
	}

	/**
	 * Define o título, otimizando
	 * @param string $title
	 */
	public static function setTitle($title) {
		Application::app()->controller->setTitle(static::otimize($title));
	}

}
