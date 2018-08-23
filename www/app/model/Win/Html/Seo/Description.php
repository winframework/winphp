<?php

namespace Win\Html\Seo;

/**
 * Auxilia a criar o Description otimizado para SEO
 */
class Description {

	public static $MAX_LENGTH = 150;
	public static $DEFAULT = '';

	/**
	 * Retorna a 'description' com tamanho ideal
	 * @param string $description
	 * @return string
	 */
	public static function otimize($description) {
		if (strlen($description) > 0) {
			return strTruncate($description, static::$MAX_LENGTH, true);
		} else {
			return static::$DEFAULT;
		}
	}

}
