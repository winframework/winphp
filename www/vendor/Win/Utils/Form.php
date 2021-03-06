<?php

namespace Win\Utils;

/**
 * Utilitário de Formulário
 */
abstract class Form
{
	/**
	 * Retorna 'checked' se os valores são iguais
	 * @param mixed $value1
	 * @param mixed $value2
	 * @return string
	 */
	public static function check($value1, $value2 = true)
	{
		return ($value1 == $value2) ? 'checked ' : '';
	}

	/**
	 * Retorna 'selected' se os valores são iguais
	 * @param mixed $value1
	 * @param mixed $value2
	 * @return string
	 */
	public static function select($value1, $value2 = true)
	{
		return ($value1 == $value2) ? 'selected ' : '';
	}
}
