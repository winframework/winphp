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
	 * @param DateTime $dateTime
	 * @param int $minutes
	 */
	public static function sumSeconds(DateTime $dateTime, $minutes = 1)
	{
		$interval = DateInterval::createFromDateString($minutes . ' ' . static::SECONDS);
		$dateTime->add($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $minutes
	 */
	public static function sumMinutes(DateTime $dateTime, $minutes = 1)
	{
		$interval = DateInterval::createFromDateString($minutes . ' ' . static::MINUTES);
		$dateTime->add($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $hours
	 */
	public static function sumHours(DateTime $dateTime, $hours = 1)
	{
		$interval = DateInterval::createFromDateString($hours . ' ' . static::HOURS);
		$dateTime->add($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $days
	 */
	public static function sumDays(DateTime $dateTime, $days = 1)
	{
		$interval = DateInterval::createFromDateString($days . ' ' . static::DAYS);
		$dateTime->add($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $weeks
	 */
	public static function sumWeeks(DateTime $dateTime, $weeks = 1)
	{
		$interval = DateInterval::createFromDateString($weeks . ' ' . static::WEEKS);
		$dateTime->add($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $months
	 */
	public static function sumMonths(DateTime $dateTime, $months = 1)
	{
		$interval = DateInterval::createFromDateString($months . ' ' . static::MONTHS);
		$dateTime->add($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $years
	 */
	public static function sumYears(DateTime $dateTime, $years = 1)
	{
		$interval = DateInterval::createFromDateString($years . ' ' . static::YEARS);
		$dateTime->add($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $minutes
	 */
	public static function subSeconds(DateTime $dateTime, $minutes = 1)
	{
		$interval = DateInterval::createFromDateString($minutes . ' ' . static::SECONDS);
		$dateTime->sub($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $minutes
	 */
	public static function subMinutes(DateTime $dateTime, $minutes = 1)
	{
		$interval = DateInterval::createFromDateString($minutes . ' ' . static::MINUTES);
		$dateTime->sub($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $hours
	 */
	public static function subHours(DateTime $dateTime, $hours = 1)
	{
		$interval = DateInterval::createFromDateString($hours . ' ' . static::HOURS);
		$dateTime->sub($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $days
	 */
	public static function subDays(DateTime $dateTime, $days = 1)
	{
		$dateTime->sub(DateInterval::createFromDateString($days . ' ' . static::DAYS));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $weeks
	 */
	public static function subWeeks(DateTime $dateTime, $weeks = 1)
	{
		$interval = DateInterval::createFromDateString($weeks . ' ' . static::WEEKS);
		$dateTime->sub($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $months
	 */
	public static function subMonths(DateTime $dateTime, $months = 1)
	{
		$interval = DateInterval::createFromDateString($months . ' ' . static::MONTHS);
		$dateTime->sub($interval);
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $years
	 */
	public static function subYears(DateTime $dateTime, $years = 1)
	{
		$interval = DateInterval::createFromDateString($years . ' ' . static::YEARS);
		$dateTime->sub($interval);
	}

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