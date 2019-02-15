<?php

namespace Win\Html\Seo;

use Win\Format\Str;

/**
 * Auxilia a criar 'Description' otimizado para SEO
 */
class Description {

	public static $default = '';

	/**
	 * Retorna a 'description' com tamanho ideal
	 * @param string $description
	 * @return string
	 */
	public static function otimize($description, $maxLength = 150) {
		if (Str::length($description) > 0) {
			return Str::truncate($description, $maxLength);
		} else {
			return static::$default;
		}
	}

}
