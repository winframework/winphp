<?php

namespace Win\Database\Sql\Statements;

use Win\Database\Sql\Statement;

/**
 * SELECT * FROM ...
 */
class Select extends Statement
{
	public function __toString()
	{
		return 'SELECT * FROM ' . $this->query->table
		. $this->query->where
		. $this->query->orderBy
		. $this->query->limit;
	}

	public function getValues()
	{
		return array_merge(
			$this->query->values,
			$this->query->where->values
		);
	}
}
