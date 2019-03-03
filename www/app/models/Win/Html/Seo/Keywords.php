<?php

namespace Win\Html\Seo;

use Win\Formats\Str;

/**
 * Auxilia a criar 'Keywords'
 */
class Keywords
{
	/**
	 * Chaves padrão.
	 * Usadas quando as chaves informadas não tenham o tamanho suficiente
	 * @var array
	 */
	public static $default = [];

	/**
	 * Retorna uma string em minúscula, separada por virgula
	 * @param string[] $keys
	 * @param int $maxLength
	 * @return string
	 */
	public static function otimize($keys, $maxLength = 100)
	{
		$keys = array_merge($keys, static::$default);
		$keys = Str::truncate(implode(', ', array_filter($keys)), $maxLength);

		return Str::lower(str_replace([',...', '...'], '', $keys));
	}
}
