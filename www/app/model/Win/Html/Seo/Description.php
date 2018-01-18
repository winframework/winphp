<?php

namespace Win\Html\Seo;

/**
 * Auxilia a criar o description otimizado para SEO
 */
class Description {

	protected static $MAX_LENGTH = 150;

	/**
	 * Retorna a description com tamanho ideal
	 * @param string $description
	 * @return string
	 */
	public static function otimize($description) {
		if (strlen($description) > 0) {
			return strTruncate($description, static::$MAX_LENGTH, true);
		} else {
			return DESCRIPTION_DEFAULT;
		}
	}

}
