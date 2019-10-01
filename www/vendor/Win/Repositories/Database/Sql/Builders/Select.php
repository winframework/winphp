<?php

namespace Win\Repositories\Database\Sql\Builders;

use Win\Repositories\Database\Sql\Builder;

/**
 * SELECT * FROM ...
 */
class Select extends Builder
{
	public function __toString()
	{
		return ($this->query->raw ??
		'SELECT * FROM ' . $this->query->table)
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
