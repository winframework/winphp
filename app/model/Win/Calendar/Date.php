<?php

namespace Win\Calendar;

/**
 * Data e Hora
 */
class Date {

	private $year = '00';
	private $month = '00';
	private $day = '00';
	private $hour = '00';
	private $minute = '00';
	private $second = '00';
	static $monthNames = array(0 => "00", "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
	static $zeroDate = '0000-00-00 00:00:00';

	const UNITS = array(
		31536000 => ['ano', 'anos'],
		2592000 => ['mês', 'mêses'],
		604800 => ['semana', 'semanas'],
		86400 => ['dia', 'dias'],
		3600 => ['hora', 'horas'],
		60 => ['minuto', 'minutos'],
		1 => ['segundo', 'segundos']
	);

	/**
	 * Inicia a data/hora de acordo com a string informada
	 * @param string
	 * @example new Data('01/01/1980 18:00');
	 */
	public function __construct($stringDate = null) {
		if ($stringDate != static::$zeroDate):
			$this->initDateTime($stringDate);
		endif;
	}

	/* METODOS DE ACESSO */

	public function getYear() {
		return $this->year;
	}

	public function getMonth() {
		return $this->month;
	}

	public function getDay() {
		return $this->day;
	}

	public function getHour() {
		return $this->hour;
	}

	public function getMinute() {
		return $this->minute;
	}

	public function getSecond() {
		return $this->second;
	}

	public static function getZeroDate() {
		return self::$zeroDate;
	}

	public function setYear($year) {
		$this->year = $year;
	}

	public function setMonth($month) {
		$this->month = $month;
	}

	public function setDay($day) {
		$this->day = $day;
	}

	public function setHour($hour) {
		$this->hour = $hour;
	}

	public function setMinute($minute) {
		$this->minute = $minute;
	}

	public function setSecond($second) {
		$this->second = $second;
	}

	public function getMonthName() {
		return self::$monthNames[(int) $this->month];
	}

	public function getMonthAbbre() {
		return strtoupper(substr($this->getMonthName(), 0, 3));
	}

	/**
	 * Inicia o objeto de acordo com a data informada
	 * @param string $stringDate
	 */
	public function initDateTime($stringDate = null) {
		if (is_null($stringDate)):
			$stringDate = date("Y-m-d H:i:s");
		endif;
		$arrayDate = explode(' ', strEscape(strStrip($stringDate)) . ' ');
		$this->initDate($arrayDate[0]);
		$this->initTime($arrayDate[1]);
	}

	/**
	 * Inicia o Dia/Mes/Ano
	 * @param string $stringDateOnly
	 */
	private function initDate($stringDateOnly) {
		if (strpos($stringDateOnly, '/') > 0) {
			/* FORMATO: DD/MM/AAAA */
			$d1 = explode('/', $stringDateOnly . '///');
			$this->year = strLengthFormat((int) $d1[2], 4);
			$this->month = strLengthFormat((int) $d1[1], 2);
			$this->day = strLengthFormat((int) $d1[0], 2);
		} else {
			/* FORMATO: AAAA-MM-DD */
			$d1 = explode('-', $stringDateOnly . '---');
			$this->year = strLengthFormat((int) $d1[0], 4);
			$this->month = strLengthFormat((int) $d1[1], 2);
			$this->day = strLengthFormat((int) $d1[2], 2);
		}
	}

	/**
	 * Inicia a Hora:Minuto:Segundo
	 * @param string $stringTimeOnly
	 */
	private function initTime($stringTimeOnly) {
		/* FORMATO: HH:MM:SS */
		$d2 = explode(':', $stringTimeOnly . ':::');
		$this->hour = strLengthFormat((int) $d2[0], 2);
		$this->minute = strLengthFormat((int) $d2[1], 2);
		$this->second = strLengthFormat((int) $d2[2], 2);
	}

	/**
	 * Retorna a data no padrao HTML BRASILEIRO: "Dia/Mês/Ano Hora:Minuto:Segundo"
	 * @return string
	 */
	public function toHtml() {
		return $this->format('d/m/y h:i:s');
	}

	/**
	 * Retorna a data no padrao SQL DATETIME: "Ano-Mês-Dia Hora:Minuto:Segundo"
	 * @return string
	 */
	public function toSql() {
		if ($this->year == 0) {
			return static::$zeroDate;
		} else {
			return $this->format('y-m-d h:i:s');
		}
	}

	/**
	 * Retorna a data com o formato padrão 'd/m/y'
	 * @return string 
	 */
	public function __toString() {
		return $this->format('d/m/y');
	}

	/**
	 * Retorna a data no formato humano
	 * @return string (ex: 4 horas atrás), (ex: daqui a 5 dias)
	 */
	public function toHumanFormat() {
		$time = time() - strtotime($this->format('d-m-y h:i:s'));
		if ($this->toSql() == static::$zeroDate) {
			return 'indisponível';
		}
		if ($time == 0) {
			return 'agora mesmo';
		}
		if ($time > 0) {
			return $this->getHumanUnits($time) . ' atrás';
		}
		if ($time < 0) {
			return 'daqui a ' . $this->getHumanUnits($time * (-1));
		}
	}

	/**
	 * Retorna a data de acordo com o formato escolhido. Ex: [d/m/y h:i:s] , [y-m-d] , [y-m-d h:i:s] , etc
	 * @param string $format
	 * @return string Data no formato desejado
	 */
	public function format($format = 'd/m/y') {
		$date = '';
		if ($this->year != 0 and $this->month != 0 and $this->day != 0) {
			$a = array('d', 'm', 'y', 'h', 'i', 's');
			$b = array($this->day, $this->month, $this->year, $this->hour, $this->minute, $this->second);
			$date = str_replace($a, $b, strtolower($format));
		}
		return $date;
	}

	/**
	 * Valida a data
	 * @return boolean retorna True se data é valida
	 */
	public function isValid() {
		if ($this->second >= 60 or $this->minute >= 60 or $this->hour >= 24) {
			return false;
		}
		return checkdate($this->getMonth(), $this->getDay(), $this->getYear());
	}

	/**
	 * Retorna true se é uma data de nascimento válida
	 * @return boolean
	 */
	public function isValidBirthday() {
		$MIN_AGE = 0;
		$MAX_AGE = 200;
		$age = date('Y') - $this->getYear();
		if ($age < $MIN_AGE or $age > $MAX_AGE) {
			return false;
		}
		return $this->isValid();
	}

	/**
	 * Soma a quantidade de dias informada
	 * @param int $days
	 */
	public function sumDays($days) {
		$this->sumTime($days, 'days');
	}

	/**
	 * Soma a quantidade de meses informada
	 * @param int $months
	 */
	public function sumMonths($months) {
		$this->sumTime($months, 'months');
	}

	/**
	 * Soma a quantidade de anos informada
	 * @param int $years
	 */
	public function sumYears($years) {
		$this->sumTime($years, 'years');
	}

	/**
	 * Soma a quantidade de tempo informada
	 * @param int $time Unidade
	 * @param string $tipo [days,month,years]
	 */
	public function sumTime($time, $tipo = 'days') {
		$newTime = strtotime('+' . $time . ' ' . $tipo, strtotime($this->format('d-m-y')));
		$this->day = date('d', $newTime);
		$this->month = date('m', $newTime);
		$this->year = date('Y', $newTime);
	}

	/**
	 * Retorna a diferença em Segundos entre duas datas
	 * @param Data $date1
	 * @param Data $date2
	 * @return float Diferença em Segundos
	 */
	public static function secondsDiff(Data $date1, Data $date2) {
		$mkTime1 = mktime($date1->getHour(), $date1->getMinute(), $date1->getSecond(), $date1->getMonth(), $date1->getDay(), $date1->getYear());
		$mkTime2 = mktime($date2->getHour(), $date2->getMinute(), $date2->getSecond(), $date2->getMonth(), $date2->getDay(), $date2->getYear());
		$mkTimeDiff = $mkTime2 - $mkTime1;

		return $mkTimeDiff;
	}

	/**
	 * Retorna a diferença em Dias entre duas datas
	 * @param Data $date1
	 * @param Data $date2
	 * @return float Diferença em Dias
	 */
	public static function daysDiff(Data $date1, Data $date2) {
		$diffInDay = ((self::secondsDiff($date1, $date2)) / 86400);
		return $diffInDay;
	}

	/**
	 * Retorna a data em unidade de tempo Humana
	 * @param string $dateInSeconds
	 * @return string
	 */
	protected function getHumanUnits($dateInSeconds) {
		foreach (static::UNITS as $unit => $measure) {
			if ($dateInSeconds < $unit) {
				continue;
			}
			$total = floor($dateInSeconds / $unit);
			if ($total > 1) {
				return $total . ' ' . $measure[1];
			} else {
				return $total . ' ' . $measure[0];
			}
		}
	}

}
