<?php

namespace Win\Format;

/**
 * Manipulador de Booleanos
 */
class Boolean {

	/**
	 * @param boolean $boolean
	 * @return string
	 */
	public static function toString($boolean) {
		return ($boolean) ? 'sim' : 'não';
	}

}
