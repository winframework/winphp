<?php

namespace Win\Database\Sql\Query;

use Win\Database\Orm\Repository;
use Win\Database\Sql\Limit;
use Win\Database\Sql\OrderBy;
use Win\Database\Sql\Query;
use Win\Database\Sql\Where;

/**
 * SELECT * FROM
 */
class Select extends Query {

	/** @var string */
	public $collumns = '*';

	/** @var Where */
	public $where;

	/** @var Limit */
	public $limit;

	/** @var OrderBy */
	public $orderBy;

	public function __construct(Repository $orm) {
		parent::__construct($orm);
		$this->where = new Where();
		$this->limit = new Limit();
		$this->orderBy = new OrderBy();
	}

	public function toString() {
		return 'SELECT ' . $this->collumns . ' FROM '
				. $this->table
				. $this->where
				. $this->orderBy
				. $this->limit;
	}

}
