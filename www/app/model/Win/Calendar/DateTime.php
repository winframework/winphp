<?php

namespace Win\Calendar;

use \DateTime as DateTimePHP;

/**
 * Data e Hora
 */
class DateTime extends DateTimePHP {

	const BR_DATE = 'd/m/Y';
	const BR_DATE_TIME = 'd/m/Y H:i:s';
	const SQL_DATE = 'Y-m-d';
	const SQL_DATE_TIME = 'Y-m-d H:i:s';
	const ZERO_DATE_TIME = '00/00/0000 00:00:00';

	/** @return string */
	public function __toString() {
		return $this->format(static::BR_DATE);
	}

	/** @return boolean */
	public function isEmpty() {
		return (boolean) ($this->format('y') < 0);
	}

	/** @return string */
	public function getMonthName() {
		return $this->formatF('%B');
	}

	/** @return string */
	public function getMonthAbbre() {
		return $this->formatF('%b');
	}

	/**
	 * Retorna a data no formato utilizado por strftime
	 * @return string
	 */
	public function formatF($format) {
		return strftime($format, $this->getTimestamp());
	}

	/** @return int */
	public function getAge($date = 'NOW') {
		return (int) $this->diff($date)->format('%y');
	}

	/** @return string */
	public function toHtml() {
		return $this->format(static::BR_DATE_TIME);
	}

	/** @return string */
	public function toSql() {
		return $this->format(static::SQL_DATE_TIME);
	}

	/**
	 * Retorna TRUE se a data é valida
	 * @param string $time
	 * @param string|null $format
	 * @return boolean
	 */
	public static function isValid($time, $format = null) {
		return (static::createFromFormat(!is_null($format) ? $format : static::BR_DATE_TIME, $time) !== false);
	}

	/**
	 * @param string $format
	 * @param string $time
	 * @return boolean|static
	 */
	public static function create($format, $time) {
		$dateTimePHP = parent::createFromFormat($format, $time);
		return new DateTime($dateTimePHP->format(static::SQL_DATE_TIME));
	}

	/**
	 * Cria uma data vazia: 00/00/0000
	 * @return static
	 */
	public static function createEmpty() {
		return static::create(static::BR_DATE_TIME, static::ZERO_DATE_TIME);
	}

}
