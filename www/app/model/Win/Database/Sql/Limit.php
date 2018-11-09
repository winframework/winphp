<?php

namespace Win\Database\Sql;

/**
 * Limit 10
 */
class Limit {

	private $limit;

	public function __construct() {
		$this->limit = '';
	}

	public function __toString() {
		if ($this->limit) {
			return ' LIMIT ' . $this->limit;
		}
		return '';
	}

	public function set($limit) {
		$this->limit = $limit;
	}

}
