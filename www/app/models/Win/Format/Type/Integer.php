<?php

namespace Win\Format\Type;

/**
 * Manipulador de Inteiros
 */
class Integer {

	/**
	 * Retorna o inteiro com a quantidade de caracteres escolhido
	 * Formatando com zeros à esquerda se necessário
	 * @param int $int
	 * @param int $length
	 * @return string
	 */
	public static function minLength($int = 0, $length = 2) {
		return str_pad($int, $length, "0", STR_PAD_LEFT);
	}

}
