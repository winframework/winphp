<?php

namespace Win\Database\Sql;

use Win\Database\Orm;
use Win\Database\Sql\Clause\Limit;
use Win\Database\Sql\Clause\OrderBy;
use Win\Database\Sql\Clause\Where;

/**
 * SELECT * FROM
 */
class Select extends Query
{
	/** @var string[] */
	public $columns;

	/** @var Where */
	public $where;

	/** @var Limit */
	public $limit;

	/** @var OrderBy */
	public $orderBy;

	public function __construct(Orm $orm)
	{
		parent::__construct($orm);
		$this->init();
	}

	protected function init()
	{
		$this->columns = ['*'];
		$this->where = new Where();
		$this->limit = new Limit();
		$this->orderBy = new OrderBy();
	}

	/** @return string */
	public function toString()
	{
		return 'SELECT ' . implode(',', $this->columns) . ' FROM '
				. $this->table
				. $this->where
				. $this->orderBy
				. $this->limit;
	}

	/** @return mixed[] */
	public function getValues()
	{
		return $this->where->values();
	}

	/** @return int */
	public function count()
	{
		$this->columns = ['count(*)'];
		$stmt = $this->connection->stmt($this, $this->getValues());
		$this->init();

		return $stmt->fetchColumn();
	}

	/** @return mixed[] */
	public function execute()
	{
		$all = $this->connection->fetchAll($this, $this->getValues());
		$this->init();

		return $all;
	}
}
