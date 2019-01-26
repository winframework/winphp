<?php

namespace Win\Database\Sql\Clause;

/**
 * WHERE id = ?
 */
class Where {

	private $filters;
	private $values;

	public function __construct() {
		$this->filters = [];
	}

	public function __toString() {
		if (count($this->filters)) {
			return ' WHERE ' . implode(' AND ', $this->filters);
		}
		return '';
	}

	public function add($column, $operator, $value) {
		$this->filters[] = $column . $operator . '?';
		$this->values[] = $value;
	}

	public function values() {
		return $this->values;
	}

}
