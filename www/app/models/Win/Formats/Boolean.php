<?php

namespace Win\Formats;

/**
 * Manipulador de Booleanos
 */
class Boolean
{
	/**
	 * @param bool $boolean
	 * @return string
	 */
	public static function toString($boolean)
	{
		return ($boolean) ? 'sim' : 'não';
	}
}