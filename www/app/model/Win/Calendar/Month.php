<?php

namespace Win\Calendar;

/**
 * Mês
 */
class Month {

	/** @var string[] */
	public static $names = [1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
	protected $month;

	const ABBRE_LENGTH = 3;

	/**
	 * @param int|string $month
	 * @return static
	 */
	public static function create($month) {
		return new static($month);
	}

	/** @param int|string $month */
	public function __construct($month) {
		$this->month = (int) $month;
	}

	/** @return string|false */
	public function getName() {
		return key_exists($this->month, static::$names) ? static::$names[$this->month] : false;
	}

	/**  @return string|false */
	public function getNameAbbre() {
		$name = $this->getName();
		if ($name) {
			return strtoupper(substr($name, 0, static::ABBRE_LENGTH));
		} else {
			return false;
		}
	}

}
