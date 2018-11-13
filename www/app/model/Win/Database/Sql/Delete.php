<?php

namespace Win\Database\Sql;

use Win\Database\Orm\Repository;
use Win\Database\Sql\Clause\Limit;
use Win\Database\Sql\Clause\Where;

/**
 * DELETE FROM
 */
class Delete extends Query {

	/** @var Where */
	public $where;

	/** @var Limit */
	public $limit;

	public function __construct(Repository $repo) {
		parent::__construct($repo);
		$this->where = new Where();
		$this->limit = new Limit();
	}

	/** @return string */
	public function toString() {
		return 'DELETE FROM '
				. $this->table
				. $this->where
				. $this->limit;
	}

	/** @return mixed[] */
	public function getValues() {
		return $this->where->values();
	}

	/** @return boolean */
	public function execute() {
		return $this->connection->query($this, $this->getValues());
	}

}
