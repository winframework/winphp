<?php

namespace Win\Html\Seo;

use Win\Formats\Str;

/**
 * Auxilia a criar 'Description' otimizado para SEO
 */
class Description
{
	/**
	 * Descrição padrão.
	 * Usadas quando a descrição informada não tenha o tamanho suficiente
	 * @var string
	 */
	public static $default = '';

	/**
	 * Retorna a 'description' com tamanho ideal
	 * @param string $description
	 * @param int $maxLength
	 * @return string
	 */
	public static function otimize($description, $maxLength = 150)
	{
		if (Str::length($description) > 0) {
			return Str::truncate($description, $maxLength);
		} else {
			return static::$default;
		}
	}
}
