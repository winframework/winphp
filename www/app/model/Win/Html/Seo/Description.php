<?php

namespace Win\Html\Seo;

use Win\Format\Str;

/**
 * Auxilia a criar o Description otimizado para SEO
 */
class Description {

	public static $DEFAULT = '';

	/**
	 * Retorna a 'description' com tamanho ideal
	 * @param string $description
	 * @return string
	 */
	public static function otimize($description, $maxLength = 150) {
		if (Str::length($description) > 0) {
			return Str::truncate($description, $maxLength);
		} else {
			return static::$DEFAULT;
		}
	}

}
