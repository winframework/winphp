<?php

namespace Win\DAO;

/**
 * Options SQL
 * Permite uso de opções SQL: Order by, Group by, etc
 */
class Option {

	private $option;

	/** Construtor */
	public function __construct() {
		$this->option = 'ORDER BY 1 DESC';
	}

	/**
	 * Define as opções
	 * @param string $option
	 */
	public function set($option) {
		$this->option = $option;
	}

	/** @return string */
	public function toSql() {
		return $this->option;
	}

}
