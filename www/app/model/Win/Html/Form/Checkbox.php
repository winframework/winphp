<?php

/**
 * Checkbox
 * Auxilia nas <input type="checkbox">
 *
 */

namespace Win\Html\Form;

class Checkbox {

	/**
	 * Retorna "checked" se os valores são iguais
	 * @param mixed $value1
	 * @param mixed $value2
	 * @return string
	 */
	public static function active($value1, $value2 = true) {
		return ($value1 == $value2) ? 'checked' : '';
	}

}
