<?php

namespace Win\Format;

/**
 * Manipulador de Strings
 */
class Str {

	/**
	 * @param string $string
	 * @return string
	 */
	public static function toUrl($string) {
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
	public static function toFileName($string) {
		return static::toUrl($string);
	}

	/**
	 * @param string $string
	 * @return int
	 */
	public static function length($string) {
		return mb_strlen($string);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function lower($string) {
		return mb_strtolower($string);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function upper($string) {
		return mb_strtoupper($string);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function camel($string) {
		$ucwords = ucwords(strtolower(str_replace(['_', '-'], ' ', $string)));
		$camel = lcfirst(preg_replace("/[^a-zA-Z0-9]/", '', $ucwords));
		return $camel;
	}

	/**
	 * Retorna a string resumida, sem cortar a última palavra
	 * @param string $string
	 * @param int $limit tamanho máximo
	 * @param bool $after define se corta depois do limit
	 * @return string
	 */
	public static function truncate($string, $limit, $after = false) {
		if (mb_strlen($string) > $limit) {
			if ($after === false) {
				$oc = mb_strrpos(mb_substr($string, 0, $limit + 1), ' ');
			} else {
				$oc = mb_strpos(mb_substr($string, $limit), ' ') + $limit;
			}
			$string = rtrim(rtrim(rtrim(mb_substr($string, 0, $oc), ','), '.')) . '...';
		}
		return $string;
	}

	/**
	 * Limpa a string, retirando espaços e tags html
	 * @param string $string
	 * @return string
	 */
	public static function strip($string) {
		return trim(strip_tags($string));
	}

	/**
	 * Formata o número com zeros à esquerda
	 * @param int $int
	 * @param int $length
	 * @return string
	 */
	public static function zeroOnLeft($int = 0, $length = 2) {
		return str_pad($int, $length, "0", STR_PAD_LEFT);
	}

}
