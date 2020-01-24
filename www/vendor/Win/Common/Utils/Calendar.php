<?php

namespace Win\Common\Utils;

use DateInterval;
use Win\Models\DateTime;

/**
 * Cálculo de Datas
 */
class DateCalc
{
	const SECONDS = 'seconds';
	const MINUTES = 'minutes';
	const HOURS = 'hours';
	const DAYS = 'days';
	const WEEKS = 'weeks';
	const MONTHS = 'months';
	const YEARS = 'years';

	/** @var string[] Unidades de tempo */
	private static $units = [
		self::SECONDS => ['segundo', 'segundos', 1],
		self::MINUTES => ['minuto', 'minutos', 60],
		self::HOURS => ['hora', 'horas', 60 * 60],
		self::DAYS => ['dia', 'dias', 24 * 60 * 60],
		self::WEEKS => ['semana', 'semanas', 7 * 24 * 60 * 60],
		self::MONTHS => ['mês', 'meses', 30 * 24 * 60 * 60],
		self::YEARS => ['ano', 'anos', 365 * 24 * 60 * 60],
	];

	/**
	 * Converte segundos em outro formato/unidade
	 * @param int $seconds
	 * @param string $format [d,m,Y,H,i]
	 */
	public static function secondsToFormat($seconds = 0, $format = 'i')
	{
		$dt1 = new DateTime('@0');
		$dt2 = new DateTime("@$seconds");

		return $dt1->diff($dt2)->format('%' . $format);
	}

	/**
	 * Retorna a data aproximada no formato humano
	 * @param DateTime|bool $date
	 * @return string (ex: 4 horas atrás), (ex: daqui a 5 dias)
	 */
	public static function toTimeAgo($date)
	{
		$timeAgo = 'indisponível';
		if (false !== $date) {
			$time = time() - strtotime($date->format(DateTime::SQL_DATE_TIME));

			if (0 === $time) {
				$timeAgo = 'agora mesmo';
			} elseif ($time > 0) {
				$timeAgo = static::toHighestUnit($time) . ' atrás';
			} else {
				$timeAgo = 'daqui a ' . static::toHighestUnit($time * (-1));
			}
		}

		return $timeAgo;
	}

	/**
	 * Converte a quantidade de segundos para a maior possível
	 * @param int $seconds
	 * @return string
	 */
	protected static function toHighestUnit($seconds)
	{
		$units = array_reverse(DateCalc::$units);
		$time = 0;
		foreach ($units as $unitName => $unitInfo) {
			if ($seconds >= $unitInfo[2]) {
				$timeTotal = floor($seconds / $unitInfo[2]);
				$isPlural = ($timeTotal > 1);
				$time = $timeTotal . ' ' . self::$units[$unitName][$isPlural];
				break;
			}
		}

		return $time;
	}
}
