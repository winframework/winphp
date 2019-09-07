<?php

namespace Win\Database\Sql\Statements;

use Win\Database\Sql\Query;
use Win\Database\Sql\Statement;

class Update implements Statement
{
	/** @var Query */
	protected $query;

	public function __construct(Query $query)
	{
		$this->query = $query;
	}

	public function __toString()
	{
		return 'UPDATE ' . $this->query->table
		. ' SET ' . $this->set()
		. $this->query->where;
	}

	/** @return string */
	protected function set()
	{
		return implode(', ', array_map(function ($column) {
			return $column . ' = ?';
		}, array_keys($this->query->values)));
	}

	public function getValues()
	{
		return array_merge(
			$this->query->values,
			$this->query->where->values
		);
	}
}
