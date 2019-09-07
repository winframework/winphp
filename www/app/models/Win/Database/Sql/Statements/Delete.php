<?php

namespace Win\Database\Sql\Statements;

use Win\Database\Sql\Query;
use Win\Database\Sql\Statement;

class Delete implements Statement
{
	/** @var Query */
	protected $query;

	public function __construct(Query $query)
	{
		$this->query = $query;
	}

	public function __toString()
	{
		return 'DELETE FROM ' . $this->query->table
		. $this->query->where;
	}

	public function getValues()
	{
		return array_merge(
			$this->query->values,
			$this->query->where->values
		);
	}
}
