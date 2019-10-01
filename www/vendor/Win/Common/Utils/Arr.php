<?php

namespace Win\Common\Utils;

/**
 * Manipulador de Arrays
 */
class Arr
{
	/**
	 * Retorna TRUE se é primeiro elemento do array
	 * @param int $i
	 * @return bool
	 */
	public static function isFirst($i)
	{
		return 0 == $i;
	}

	/**
	 * Retorna TRUE se é último elemento do array
	 * @param int $i
	 * @param mixed[] $array
	 * @return bool
	 */
	public static function isLast($i, $array)
	{
		return $i == count($array) - 1;
	}

	/**
	 * Retorna TRUE se o array é associativo
	 * @param mixed[] $array
	 * @return bool
	 */
	public static function isAssoc($array)
	{
		return array_keys($array) !== range(0, count($array) - 1);
	}
}