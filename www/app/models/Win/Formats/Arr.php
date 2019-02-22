<?php

namespace Win\Formats;

/**
 * Manipulador de Arrays
 */
class Arr
{
	/**
	 * @param int $i
	 * @return bool
	 */
	public static function isFirst($i)
	{
		return 0 == $i;
	}

	/**
	 * @param int $i
	 * @param mixed[] $array
	 * @return bool
	 */
	public static function isLast($i, $array)
	{
		return $i == count($array) - 1;
	}

	/**
	 * @param mixed[] $array
	 * @return bool
	 */
	public static function isAssoc($array)
	{
		return array_keys($array) !== range(0, count($array) - 1);
	}
}
