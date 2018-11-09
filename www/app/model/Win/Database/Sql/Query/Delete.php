<?php

namespace Win\Database\Sql\Query;

use Win\Database\Sql\Limit;
use Win\Database\Sql\Query;
use Win\Database\Sql\Where;

/**
 * DELETE FROM
 */
class Delete extends Query {

	/** @var Where */
	public $where;

	/** @var Limit */
	public $limit;

	public function __construct($table) {
		parent::__construct($table);
		$this->where = new Where();
		$this->limit = new Limit();
	}

	public function __toString() {
		return 'DELETE FROM '
				. $this->table
				. $this->where
				. $this->limit;
	}

}
