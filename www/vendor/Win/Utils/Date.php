<?php

namespace Win\Utils;

use DateTime;

/**
 * Utilitário de Data e Hora
 */
abstract class Date
{
	const FORMAT_MONTH_ABBR = '%B';
	const FORMAT_MONTH_NAME = '%b';
	
	/**
	 * Cra data no formato desejado
	 * @param string $formatFrom
	 * @param string $date
	 * @param string $formatTo
	 * @return string|null
	 */
	public static function create($formatFrom, $date, $formatTo = 'Y-m-d H:i:s')
	{
		$dateTime = DateTime::createFromFormat($formatFrom, $date);
		return $dateTime ? $dateTime->format($formatTo) : null;
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
	 * @param string $date
	 * @param string $format
	 * @return string
	 */
	public static function formatF($date, $format)
	{
		return strftime($format, strtotime($date));
	}

	/**
	 * Retorna a idade
	 * @param string $date1 Data de Nascimento
	 * @param string $date2
	 * @return int
	 */
	public static function age($date1, $date2 = 'now')
	{
		return (new DateTime($date1))->diff(new DateTime($date2))->y;
	}

	/**
	 * Retorna TRUE se a data é valida
	 * @param string $date
	 * @param string|null $format
	 * @return bool
	 */
	public static function isValid($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
}
