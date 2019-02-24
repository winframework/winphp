<?php

namespace Win\Database\Sql\Queries;

use Win\Database\Orm;
use Win\Database\Sql\Clauses\Limit;
use Win\Database\Sql\Clauses\Where;
use Win\Database\Sql\Query;

/**
 * DELETE FROM
 */
class Delete extends Query
{
	/** @var Where */
	public $where;

	/** @var Limit */
	public $limit;

	public function __construct(Orm $orm)
	{
		parent::__construct($orm);
		$this->where = new Where();
		$this->limit = new Limit();
	}

	/** @return string */
	public function toString()
	{
		return 'DELETE FROM '
				. $this->table
				. $this->where
				. $this->limit;
	}

	/** @return mixed[] */
	public function getValues()
	{
		return $this->where->values();
	}

	/** @return bool */
	public function execute()
	{
		return $this->connection->query($this, $this->getValues());
	}
}