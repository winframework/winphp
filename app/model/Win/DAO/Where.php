<?php

namespace Win\DAO;

/**
 * Where SQL
 * Cria condições SQL
 */
class Where {

	private $condition = null;
	private $values = [];

	/** Construtor */
	public function __construct() {
		$this->condition = null;
		$this->values = [];
	}

	/**
	 * Adiciona novos filtros
	 * @param string $condition
	 * @param mixed[] $values
	 */
	public function filter($condition, $values) {
		if (count($values)) {
			if (!is_null($this->condition)) {
				$this->condition .= ' AND ';
			}
			$this->condition .= $condition;
			$this->values = $this->values + $values;
		}
	}

	/** @return string */
	public function toSql() {
		if (!is_null($this->condition)) {
			return ' WHERE ' . $this->condition . ' ';
		}
		return ' ';
	}

	/** @return mixed[] */
	public function values() {
		return array_values($this->values);
	}

}
