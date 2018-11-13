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

	/** @var string[] */
	public $collumns;

	/** @var Where */
	public $where;

	/** @var Limit */
	public $limit;

	/** @var OrderBy */
	public $orderBy;

	public function __construct(Repository $repo) {
		parent::__construct($repo);
		$this->init();
	}

	protected function init() {
		$this->collumns = ['*'];
		$this->where = new Where();
		$this->limit = new Limit();
		$this->orderBy = new OrderBy();
	}

	/** @return string */
	public function toString() {
		return 'SELECT ' . implode(',', $this->collumns) . ' FROM '
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
		$this->collumns = ['count(*)'];
		$stmt = $this->connection->stmt($this, $this->getValues());
		$this->init();
		return $stmt->fetchColumn();
	}

	/** @return mixed[] */
	public function execute() {
		$all = $this->connection->fetchAll($this, $this->getValues());
		$this->init();
		return $all;
	}

}
