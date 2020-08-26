<?php

namespace Win\Common\Utils;
/**
 * Manipulador de Inteiros
 */
abstract class Integer
{
	/**
	 * Retorna o inteiro com a quantidade de caracteres escolhido
	 * Formatando com zeros à esquerda se necessário
	 * @param int $int
	 * @param int $length
	 * @return string
	 */
	public static function minLength($int = 0, $length = 2)
	{
		return str_pad($int, $length, '0', STR_PAD_LEFT);
	}

	/**
	 * Retorna o Id com no mínimo 6 dígitos
	 * @param int $id
	 * @return string
	 */
	public static function formatId($id)
	{
		return str_pad($id, 6, '0', STR_PAD_LEFT);
	}
}
