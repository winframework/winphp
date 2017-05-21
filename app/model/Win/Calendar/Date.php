<?php

namespace Win\Calendar;

/**
 * Data e Hora
 */
class Date {

	public $year = '00';
	public $month = '00';
	public $day = '00';
	public $hour = '00';
	public $minute = '00';
	public $second = '00';
	static $monthNames = [0 => "00", "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

	/** @var int */
	public static $units = [
		'seconds' => 1,
		'minutes' => 60,
		'hours' => 60 * 60,
		'days' => 60 * 60 * 24,
		'weeks' => 60 * 60 * 24 * 7,
		'months' => 60 * 60 * 24 * 30,
		'years' => 60 * 60 * 24 * 365
	];

	/**
	 * Inicia a data/hora de acordo com a string informada
	 * @param string
	 * @example new Data('01/01/1980 18:00');
	 */
	public function __construct($stringDate = null) {
		$this->initDateTime($stringDate);
	}

	/* METODOS DE ACESSO */

	public function isEmpty() {
		return (boolean) $this->year == 0;
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
		$arrayDate = explode(' ', strip_tags($stringDate) . ' ');
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
			$date = explode('/', $stringDateOnly . '///');
			array_reverse($date);
		} else {
			/* FORMATO: AAAA-MM-DD */
			$date = explode('-', $stringDateOnly . '---');
		}
		$this->year = strLengthFormat((int) $date[0], 4);
		$this->month = strLengthFormat((int) $date[1], 2);
		$this->day = strLengthFormat((int) $date[2], 2);
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
	 * Retorna a data com o formato padrão 'd/m/y'
	 * @return string 
	 */
	public function __toString() {
		return $this->format('d/m/y');
	}

	/**
	 * Retorna a data no padrao SQL DATETIME
	 * @return string
	 */
	public function toSql() {
		return $this->format('y-m-d h:i:s');
	}

	/**
	 * Retorna a data no padrao TimeAgo
	 * @return string
	 */
	public function toTimeAgo() {
		return TimeAgo::format($this);
	}

	/**
	 * Retorna a data de acordo com o formato escolhido. Ex: [d/m/y h:i:s] , [y-m-d] , [y-m-d h:i:s] , etc
	 * @param string $format
	 * @return string Data no formato desejado
	 */
	public function format($format = 'd/m/y') {
		$date = null;
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
	 * Soma a quantidade de tempo informada
	 * @param int $time
	 * @param string $unit [days,month,years,etc]
	 */
	public function sumTime($time, $unit = 'days') {
		$newTime = strtotime('+' . $time . ' ' . $unit, strtotime($this->format('d-m-y h:i:s')));
		$this->initDateTime(date('Y-m-d H:i:s', $newTime));
	}

	/**
	 * Retorna o mktime da data
	 * @return int
	 */
	public function toMktime() {
		return mktime($this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year);
	}

	/**
	 * Retorna a diferença em Segundos entre as duas datas
	 * @param Date $date
	 * @param string $unit [days,month,years,etc]
	 * @return int diferença na unidade desejada
	 */
	public function diff(Date $date, $unit = 'seconds') {
		$mkTimeDiff = $this->toMktime() - $date->toMktime();
		return $this->convertSeconds($mkTimeDiff, $unit);
	}

	/**
	 * Converte segundos em outra unidade
	 * @param int $seconds
	 * @param string $newUnit [days,month,years,etc]
	 */
	public static function convertSeconds($seconds = 0, $newUnit = 'minutes') {
		return $seconds / static::$units[$newUnit];
	}

}
