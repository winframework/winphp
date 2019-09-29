<?php

namespace Win\Core\Common\Utils;

/**
 * Auxilia a criar o título otimizado para SEO
 */
class Seo
{
	public static $titlePrefix = '';
	public static $titleSuffix = '';
	public static $keywords = [];

	/**
	 * Descrição padrão.
	 * Usadas quando a descrição informada não tenha o tamanho suficiente
	 * @var string
	 */
	public static $description = '';

	/**
	 * Retorna o título com o nome da aplicação no final
	 * Mantendo o máximo de caracteres
	 * @param string $title
	 * @param string $maxLength
	 * @return string
	 */
	public static function title($title, $maxLength = 70)
	{
		$staticLength = Str::length(static::$titlePrefix) + Str::length(static::$titleSuffix);
		$maxLength = $maxLength - $staticLength;

		return static::$titlePrefix . Str::truncate($title, $maxLength) . static::$titleSuffix;
	}

	/* Chaves padrão.
		* Usadas quando as chaves informadas não tenham o tamanho suficiente
		* @var array
		*/

	/**
	 * Retorna uma string em minúscula, separada por virgula
	 * @param string[] $keys
	 * @param int $maxLength
	 * @return string
	 */
	public static function keywords($keys, $maxLength = 100)
	{
		$keys = array_merge($keys, static::$keywords);
		$keys = Str::truncate(implode(', ', array_filter($keys)), $maxLength);

		return Str::lower(str_replace([',...', '...'], '', $keys));
	}

	/**
	 * Retorna a 'description' com tamanho ideal
	 * @param string $description
	 * @param int $maxLength
	 * @return string
	 */
	public static function description($description, $maxLength = 150)
	{
		if (Str::length($description) > 0) {
			return Str::truncate($description, $maxLength);
		} else {
			return static::$description;
		}
	}
}
