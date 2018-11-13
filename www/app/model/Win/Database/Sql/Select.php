<?php

namespace Win\Database\Sql;

use Win\Database\Orm\Repository;
use Win\Database\Sql\Clause\Limit;
use Win\Database\Sql\Clause\OrderBy;
use Win\Database\Sql\Clause\Where;
use Win\Database\Sql\Query;

/**
 * SELECT * FROM
 */
class Select extends Query {

	/** @var string */
	public $collumns;

	/** @var Where */
	public $where;

	/** @var Limit */
	public $limit;

	/** @var OrderBy */
	public $orderBy;

	public function __construct(Repository $orm) {
		parent::__construct($orm);
		$this->flush();
	}

	protected function flush() {
		$this->collumns = '*';
		$this->where = new Where();
		$this->limit = new Limit();
		$this->orderBy = new OrderBy();
	}

	/** @return string */
	public function toString() {
		return 'SELECT ' . $this->collumns . ' FROM '
				. $this->table
				. $this->where
				. $this->orderBy
				. $this->limit;
	}

	/** @return mixed[] */
	public function getValues() {
		return $this->where->values();
	}

	/** @return int */
	public function count() {
		$this->collumns = 'count(*)';
		$stmt = $this->connection->stmt($this, $this->getValues());
		$this->flush();
		return $stmt->fetchColumn();
	}

	/** @return mixed[] */
	public function execute() {
		$all = $this->connection->fetchAll($this, $this->getValues());
		$this->flush();
		return $all;
	}

}
