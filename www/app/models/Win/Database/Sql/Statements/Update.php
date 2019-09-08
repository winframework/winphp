<?php

namespace Win\Database\Sql\Statements;

use Win\Database\Sql\Statement;

/**
 * UPDATE ... SET ...
 */
class Update extends Statement
{
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
