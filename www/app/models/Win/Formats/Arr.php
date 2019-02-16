<?php

namespace Win\Formats;

/**
 * Manipulador de Arrays
 *
 */
class Arr {

	/**
	 * @param int $i
	 * @return boolean
	 */
	public static function isFirst($i) {
		return ($i == 0);
	}

	/**
	 * @param int $i
	 * @param mixed[] $array
	 * @return boolean
	 */
	public static function isLast($i, $array) {
		return ($i == count($array) - 1);
	}

	/**
	 * @param mixed[] $array
	 * @return boolean
	 */
	public static function isAssoc($array) {
		return array_keys($array) !== range(0, count($array) - 1);
	}

}
