<?php

namespace Win\Database\Sql\Statements;

use Win\Database\Sql\Query;
use Win\Database\Sql\Statement;

class Select implements Statement
{
	/** @var Query */
	protected $query;

	public function __construct(Query $query)
	{
		$this->query = $query;
	}

	public function __toString()
	{
		return 'SELECT * FROM ' . $this->query->table
		. $this->query->where
		. $this->query->orderBy;
	}

	public function getValues()
	{
		return array_merge(
			$this->query->values,
			$this->query->where->values
		);
	}
}
