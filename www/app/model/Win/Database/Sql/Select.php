<?php

namespace Win\Database\Sql;

/**
 * SELECT * FROM
 */
class Select {

	private $table = '';
	public $collumns = '*';

	/** @var Where */
	public $where;

	/** @var OrderBy */
	public $orderBy;

	/** @var Limit */
	public $limit;

	public function __construct($table) {
		$this->table = $table;
		$this->where = new Where();
		$this->orderBy = new OrderBy();
		$this->limit = new Limit();
	}

	public function __toString() {
		return 'SELECT ' . $this->collumns . ' FROM '
				. $this->table
				. $this->where
				. $this->orderBy
				. $this->limit;
	}

}
