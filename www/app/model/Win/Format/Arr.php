<?php

namespace Win\Format;

/**
 * Manipulador de Arrays
 *
 */
class Arr {

	public static function isFirst($i) {
		return ($i == 0);
	}

	public static function isLast($i, &$array) {
		return ($i == count($array) - 1);
	}

}
