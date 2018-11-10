<?php

namespace Win\Database\Sql\Query;

use Win\Database\Sql\Query;

/**
 * UPDATE SET
 */
class Update extends Query {

	/** @var mixed[] */
	protected $mapRow;

	public function __construct($orm) {
		parent::__construct($orm);
		$this->mapRow = $orm->mapRow($orm->getModel());
	}

	public function toString() {
		$keys = array_keys($this->mapRow);
		$params = [];
		foreach ($keys as $key) {
			$params[] = $key . ' = ?';
		}
		return 'UPDATE ' . $this->table
				. ' SET ' . implode(', ', $params)
				. ' WHERE id = ? ';
	}

	public function getValues() {
		$values = array_values($this->mapRow);
		$values[] = $this->orm->getModel()->getId();
		return $values;
	}

}
