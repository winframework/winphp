<?php

namespace Win\Database\Sql\Statements;

use Win\Database\Sql\Query;
use Win\Database\Sql\Statement;

/**
 * DELETE FROM ...
 */
class Delete extends Statement
{
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
