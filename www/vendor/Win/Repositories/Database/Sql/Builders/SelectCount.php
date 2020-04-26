<?php

namespace Win\Repositories\Database\Sql\Builders;

/**
 * SELECT COUNT(*) FROM ...
 */
class SelectCount extends Select
{
	public function __toString()
	{
		return ($this->query->raw ??
			'SELECT COUNT(*) FROM ' . $this->query->table)
			. $this->query->where;
	}
}
