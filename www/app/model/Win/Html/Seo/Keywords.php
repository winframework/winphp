<?php

namespace Win\Html\Seo;

use Win\Format\Str;

/**
 * Auxilia gerenciamento de Keywords
 */
class Keywords {

	public static $default = [];

	/**
	 * Retorna uma string em minúscula, separada por virgula
	 * @param string[] $keys
	 * @return string
	 */
	public static function otimize($keys, $maxLength = 100) {
		return Str::lower(str_replace([',...', '...'], '', Str::truncate(implode(', ', array_filter(array_merge($keys, static::$default))), $maxLength)));
	}

}
