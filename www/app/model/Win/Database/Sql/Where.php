<?php

namespace Win\Database\Sql;

/**
 * WHERE id = ?
 */
class Where {

	private $filters;

	public function __construct() {
		$this->filters = [];
	}

	public function __toString() {
		if (count($this->filters)) {
			return ' WHERE ' . implode(' AND ', $this->filters);
		}
		return '';
	}

	public function add($collumn, $operator, $value) {
		$this->filters[] = $collumn . $operator . $value;
	}

}
