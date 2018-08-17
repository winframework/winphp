<?php

namespace Win\Calendar;

use DateInterval;

/**
 * Calculo de Datas
 */
class DateCalc {

	const SECONDS = 'seconds';
	const MINUTES = 'minutes';
	const HOURS = 'hours';
	const DAYS = 'days';
	const WEEKS = 'weeks';
	const MONTHS = 'months';
	const YEARS = 'years';

	/** @var string[] Unidades de tempo */
	static private $units = array(
		self::SECONDS => ['segundo', 'segundos', 1],
		self::MINUTES => ['minuto', 'minutos', 60],
		self::HOURS => ['hora', 'horas', 60 * 60],
		self::DAYS => ['dia', 'dias', 24 * 60 * 60],
		self::WEEKS => ['semana', 'semanas', 7 * 24 * 60 * 60],
		self::MONTHS => ['mês', 'mêses', 30 * 24 * 60 * 60],
		self::YEARS => ['ano', 'anos', 365 * 24 * 60 * 60]
	);

	/**
	 * @param DateTime $dateTime
	 * @param int $minutes
	 */
	public static function sumSeconds(DateTime $dateTime, $minutes = 1) {
		$dateTime->add(DateInterval::createFromDateString($minutes . ' seconds'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $minutes
	 */
	public static function sumMinutes(DateTime $dateTime, $minutes = 1) {
		$dateTime->add(DateInterval::createFromDateString($minutes . ' minutes'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $hours
	 */
	public static function sumHours(DateTime $dateTime, $hours = 1) {
		$dateTime->add(DateInterval::createFromDateString($hours . ' hours'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $days
	 */
	public static function sumDays(DateTime $dateTime, $days = 1) {
		$dateTime->add(DateInterval::createFromDateString($days . ' days'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $weeks
	 */
	public static function sumWeeks(DateTime $dateTime, $weeks = 1) {
		$dateTime->add(DateInterval::createFromDateString($weeks . ' weeks'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $months
	 */
	public static function sumMonths(DateTime $dateTime, $months = 1) {
		$dateTime->add(DateInterval::createFromDateString($months . ' months'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $years
	 */
	public static function sumYears(DateTime $dateTime, $years = 1) {
		$dateTime->add(DateInterval::createFromDateString($years . ' years'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $minutes
	 */
	public static function subSeconds(DateTime $dateTime, $minutes = 1) {
		$dateTime->sub(DateInterval::createFromDateString($minutes . ' seconds'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $minutes
	 */
	public static function subMinutes(DateTime $dateTime, $minutes = 1) {
		$dateTime->sub(DateInterval::createFromDateString($minutes . ' minutes'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $hours
	 */
	public static function subHours(DateTime $dateTime, $hours = 1) {
		$dateTime->sub(DateInterval::createFromDateString($hours . ' hours'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $days
	 */
	public static function subDays(DateTime $dateTime, $days = 1) {
		$dateTime->sub(DateInterval::createFromDateString($days . ' days'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $weeks
	 */
	public static function subWeeks(DateTime $dateTime, $weeks = 1) {
		$dateTime->sub(DateInterval::createFromDateString($weeks . ' weeks'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $months
	 */
	public static function subMonths(DateTime $dateTime, $months = 1) {
		$dateTime->sub(DateInterval::createFromDateString($months . ' months'));
	}

	/**
	 * @param DateTime $dateTime
	 * @param int $years
	 */
	public static function subYears(DateTime $dateTime, $years = 1) {
		$dateTime->sub(DateInterval::createFromDateString($years . ' years'));
	}

	/**
	 * Converte segundos em outra unidade
	 * @param int $seconds
	 * @param string $format [d,m,Y,H,i]
	 */
	public static function convertSeconds($seconds = 0, $format = 'i') {
		$dt1 = new DateTime("@0");
		$dt2 = new DateTime("@$seconds");
		return $dt1->diff($dt2)->format('%' . $format);
	}

	/**
	 * Retorna a data no formato humano
	 * @return string (ex: 4 horas atrás), (ex: daqui a 5 dias)
	 */
	public static function toTimeAgo(DateTime $date) {
		if ($date === false) {
			return 'indisponível';
		}
		$time = time() - strtotime($date->format(DateTime::SQL_DATE_TIME));

		if ($time == 0) {
			return 'agora mesmo';
		}
		if ($time > 0) {
			return static::getTime($time) . ' atrás';
		}
		if ($time < 0) {
			return 'daqui a ' . static::getTime($time * (-1));
		}
	}

	/**
	 * Retorna a data em unidade de tempo Humana
	 * @param int $seconds
	 * @return string Ex: 15 horas
	 */
	protected static function getTime($seconds) {
		$units = array_reverse(DateCalc::$units);
		foreach ($units as $unitName => $unitInfo) {
			if ($seconds >= $unitInfo[2]) {
				$timeTotal = floor($seconds / $unitInfo[2]);
				$isPlural = ($timeTotal > 1);
				return $timeTotal . ' ' . self::$units[$unitName][$isPlural];
			}
		}
		return 0;
	}

}
