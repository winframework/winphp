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
	public static function toCamel($string) {
		$ucwords = ucwords(strtolower(str_replace(['_', '-'], ' ', $string)));
		$camel = lcfirst(preg_replace("/[^a-zA-Z0-9]/", '', $ucwords));
		return $camel;
	}

	/**
	 * Retorna a string resumida, sem cortar a última palavra
	 * @param string $string
	 * @param int $limit tamanho máximo permitido
	 * @param bool $after define se corta depois do tamanho máximo
	 * @return string
	 */
	public static function truncate($string, $limit, $after = false) {
		if (strlen($string) > $limit) {
			if ($after === false) {
				$lenght = strrpos(substr($string, 0, $limit), ' ');
			} else {
				$lenght = strpos(substr($string, $limit), ' ') + $limit;
			}
			$string = rtrim(rtrim(substr($string, 0, $lenght), ','), '.') . '...';
		}
		return $string;
	}

}
