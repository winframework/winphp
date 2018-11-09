<?php

namespace Win\Database\Sql\Query;

use Win\Database\Sql\OrderBy;
use Win\Database\Sql\Query;

/**
 * SELECT * FROM
 */
class Select extends Query {

	/** @var string */
	public $collumns = '*';

	/** @var OrderBy */
	public $orderBy;

	public function __construct($table) {
		parent::__construct($table);
		$this->orderBy = new OrderBy();
	}

	public function __toString() {
		return 'SELECT ' . $this->collumns . ' FROM '
				. $this->table
				. $this->where
				. $this->orderBy
				. $this->limit;
	}

}
