<?php

namespace Win\Calendar;

/**
 * Formata data para Humano
 */
class TimeAgo extends DateFormat {

	/** @var string[] Nome das unidades de tempo */
	static private $unitsName = array(
		'seconds' => ['segundo', 'segundos'],
		'minutes' => ['minuto', 'minutos'],
		'hours' => ['hora', 'horas'],
		'days' => ['dia', 'dias'],
		'weeks' => ['semana', 'semanas'],
		'months' => ['mês', 'mêses'],
		'years' => ['ano', 'anos']
	);

	/**
	 * Retorna a data no formato humano
	 * @return string (ex: 4 horas atrás), (ex: daqui a 5 dias)
	 */
	public static function format(Date $date) {
		if ($date->isEmpty()) {
			return 'indisponível';
		}
		$time = time() - strtotime($date->format('d-m-y h:i:s'));

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
		$units = array_reverse(Date::$units);
		foreach ($units as $unitName => $unitTotal) {
			if ($seconds > $unitTotal) {
				$timeTotal = floor($seconds / $unitTotal);
				$isPlural = ($timeTotal > 1);
				return $timeTotal . ' ' . static::$unitsName[$unitName][$isPlural];
			}
		}
		return 0;
	}

}
