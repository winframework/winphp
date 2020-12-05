<?php

namespace Win\Utils;

/**
 * Utilitário de Strings
 */
abstract class Str
{
	const TRUNCATE_BEFORE = 0;
	const TRUNCATE_AFTER = 1;

	/**
	 * @param string $string
	 * @return string
	 */
	public static function toUrl($string)
	{
		$url = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
		$url = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $url);
		$url = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $url)), '_');
		$url = preg_replace("/[\/_| -]+/", '-', $url);
		$url = strtolower(trim($url, '-'));

		return $url;
	}

	/**
	 * @param string $string
	 * @return int
	 */
	public static function length($string)
	{
		return mb_strlen($string);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function lower($string)
	{
		return mb_strtolower($string);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function upper($string)
	{
		return mb_strtoupper($string);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function lowerCamel($string)
	{
		preg_match('/^_*/', $string, $begin);

		return $begin[0] . lcfirst(static::camel($string));
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function camel($string)
	{
		$string = ucwords(mb_strtolower(trim(str_replace(['-', '_'], ' ', $string))));

		return preg_replace('/[^a-zA-Z0-9]/', '', $string);
	}

	/**
	 * Retorna a string resumida, sem cortar a última palavra
	 * @param string $string
	 * @param int $limit tamanho máximo
	 * @param int $mode TRUNCATE_BEFORE | TRUNCATE_AFTER
	 * @return string
	 */
	public static function truncate($string, $limit, $mode = self::TRUNCATE_BEFORE)
	{
		$string = strip_tags($string);
		if (mb_strlen($string) <= $limit) {
			return $string;
		}
		if ($mode === static::TRUNCATE_BEFORE) {
			$limit = mb_strrpos(mb_substr($string, 0, $limit + 1), ' ');
		} elseif ($mode === static::TRUNCATE_AFTER) {
			$limit = mb_strpos(mb_substr($string, $limit), ' ') + $limit;
		}
		return rtrim(mb_substr($string, 0, $limit), ' ,.!?') . '...';
	}

	/**
	 * Limpa a string, retirando espaços e tags html
	 * @param string $string
	 * @return string
	 */
	public static function strip($string)
	{
		return trim(strip_tags($string));
	}
}
