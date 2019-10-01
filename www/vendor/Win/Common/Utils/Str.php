<?php

namespace Win\Common\Utils;

/**
 * Manipulador de Strings
 */
class Str
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
		$url = preg_replace("/[\/_| -]+/", '-', $url);
		$url = strtolower(trim($url, '-'));

		return $url;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function toFileName($string)
	{
		return static::toUrl($string);
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

	public static function lowerDashed($string)
	{
		return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $string));
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function camel($string)
	{
		$string = ucwords(strtolower(trim(str_replace(['-', '_'], ' ', $string))));

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
		if (mb_strlen($string) > $limit) {
			$string = strip_tags($string);
			$limit = static::calcLimit($string, $limit, $mode);
			$string = rtrim(mb_substr($string, 0, $limit), ' ,.!?') . '...';
		}

		return $string;
	}

	/**
	 * Calcula o limite ideal
	 * @param string $string
	 * @param int $limit
	 * @param int $mode
	 * @return int
	 */
	protected static function calcLimit($string, $limit, $mode)
	{
		if ($mode === static::TRUNCATE_BEFORE) {
			$limit = mb_strrpos(mb_substr($string, 0, $limit + 1), ' ');
		} elseif ($mode === static::TRUNCATE_AFTER) {
			$limit = mb_strpos(mb_substr($string, $limit), ' ') + $limit;
		}

		return $limit;
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
