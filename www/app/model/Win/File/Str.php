<?php

namespace Win\File;

class Str {

	/**
	 * Converte uma string para um nome válido
	 * @param string $string
	 * @return string
	 */
	public static function toValidName($string) {
		return trim(strToURL($string), '-');
	}

}
