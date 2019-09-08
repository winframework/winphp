<?php

namespace Win\Database\Sql\Statements;

use Win\Database\Sql\Statement;

/**
 * INSERT INTO ...
 */
class Insert extends Statement
{
	protected $values;

	public function __construct($query)
	{
		parent::__construct($query);
		$this->values = array_merge(
			$this->query->values,
			$this->query->where->values
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
