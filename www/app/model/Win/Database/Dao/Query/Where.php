<?php

namespace Win\Database\Dao\Query;

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
			return ' WHERE ' . implode(' ', $this->filters);
		}
		return '';
	}

	public function add($filter) {
		$this->filters[] = $filter;
	}

}
