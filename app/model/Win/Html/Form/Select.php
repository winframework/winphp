<?php

/**
 * Select
 * Auxilia nas <select>
 *
 */

namespace Win\Html\Form;

class Select {

	/**
	 * Retorna selected="true" se os valores são iguais
	 * @param mixed $value1
	 * @param mixed $value2
	 */
	public static function active($value1, $value2 = true) {
		if ($value1 == $value2) {
			echo 'selected="true"';
		}
	}

}
