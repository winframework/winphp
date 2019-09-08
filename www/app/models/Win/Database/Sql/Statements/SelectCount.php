<?php

namespace Win\Database\Sql\Statements;

/**
 * SELECT COUNT(*) FROM ...
 */
class SelectCount extends Select
{
	public function __toString()
	{
		return 'SELECT COUNT(*) FROM ' . $this->query->table
		. $this->query->where;
	}
}
