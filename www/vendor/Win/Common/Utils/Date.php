<?php

namespace Win\Common\Utils;

use DateTime;

/**
 * Data e Hora
 */
class Date
{
	/**
	 * Cra data no formato desejado
	 * @param string $date
	 * @param string $formatFrom
	 * @param string $formatTo
	 * @return string
	 */
	public static function create($date, $formatFrom, $formatTo = 'Y-m-d H:i:s')
	{
		return DateTime::createFromFormat($formatFrom, $date)->format($formatTo);
	}

	/**
	 * Formata a data
	 * @param string $date
	 * @param string $format
	 */
	public static function format($date, $format)
	{
		if ($date) {
			return date($format, strtotime($date));
		}
	}

	/**
	 * Retorna a data no formato utilizado por strftime
	 * @param string $dateTimePHP
	 * @param string $format
	 * @return string
	 */
	public static function formatF($date, $format)
	{
		return strftime($format, strtotime($date));
	}

	/**
	 * Retorna o nome do mês
	 * @param string $date
	 * @return string
	 */
	public static function month($date)
	{
		return static::formatF($date, '%B');
	}

	/**
	 * Retorna o nome do mês abreviado
	 * @param string $date
	 * @return string
	 */
	public static function monthAbbr($date)
	{
		return static::formatF($date, '%b');
	}

	/**
	 * Retorna a idade
	 * @param $date1 Data de Nascimento
	 * @param $date2
	 * @return int
	 */
	public static function age($date1, $date2 = 'today')
	{
		return date_diff(date_create($date1), date_create($date2))->y;
	}

	/**
	 * Retorna TRUE se a data é valida
	 * @param string $time
	 * @param string|null $format
	 * @return bool
	 */
	public static function isValid($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
}
