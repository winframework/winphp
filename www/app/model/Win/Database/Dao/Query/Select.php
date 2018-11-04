<?php

namespace Win\Database\Dao\Query;

/**
 * SELECT * FROM
 */
class Select {

	private $table = '';

	/** @var Where */
	public $where;

	/** @var OrderBy */
	public $orderBy;

	public function __construct($table) {
		$this->table = $table;
		$this->where = new Where();
		$this->orderBy = new OrderBy();
	}

	public function __toString() {
		return 'SELECT * FROM ' . $this->table . $this->where . $this->orderBy;
	}

}
