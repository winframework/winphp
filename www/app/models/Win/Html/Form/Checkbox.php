<?php

namespace Win\Html\Form;

/**
 * Checkbox
 * <input type="checkbox">
 */
class Checkbox extends Input
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

	/** @return string */
	public function html()
	{
		return '<input type="checkbox" />';
	}
}
