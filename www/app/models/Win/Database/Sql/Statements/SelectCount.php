<?php

namespace Win\Database\Sql\Statements;

class SelectCount extends Select
{
	public function __toString()
	{
		return 'SELECT count(*) FROM ' . $this->query->table
		. $this->query->where;
	}
}
