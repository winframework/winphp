<?php

namespace Win\Database\Sql\Statements;

use Win\Database\Sql\Query;
use Win\Database\Sql\Statement;

class Insert implements Statement
{
	protected $values = [];

	/** @var Query */
	protected $query;

	public function __construct(Query $query)
	{
		$this->query = $query;
		$this->values = array_merge(
			$query->values,
			$query->where->values
		);
	}

	public function __toString()
	{
		return 'INSERT INTO ' . $this->query->table
		. ' (' . implode(',', array_keys($this->values)) . ')'
		. ' VALUES (' . implode(', ', $this->getBindParams()) . ')';
	}

	public function getValues()
	{
		return $this->values;
	}

	/**
	 * @return string[]
	 * @example return ['?','?','?']
	 */
	protected function getBindParams()
	{
		return str_split(str_repeat('?', count($this->values)));
	}
}
