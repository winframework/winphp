<?php

namespace Win\Calendar;

/**
 * Mês
 */
class Month {

	/** @var string[] */
	public static $names = [1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

	const ABBRE_LENGTH = 3;

	/**
	 * @param int $month
	 * @return string|false
	 */
	public static function getName($month) {
		return key_exists($month, static::$names) ? static::$names[$month] : false;
	}

	/**
	 * @param int $month
	 * @return string|false
	 */
	public static function getNameAbbre($month) {
		$name = static::getName($month);
		if ($name) {
			return strtoupper(substr($name, 0, static::ABBRE_LENGTH));
		} else {
			return false;
		}
	}

}
