<?php

namespace Win\Html\Form;

/**
 * Checkbox
 * Auxilia nas <input type="checkbox">
 *
 */
class Checkbox {

	/**
	 * Retorna checked="true" se os valores são iguais
	 * @param mixed $value1
	 * @param mixed $value2
	 */
	public static function active($value1, $value2 = true) {
		if ($value1 == $value2) {
			return 'checked="true"';
		}
	}

}
