<?php

namespace Win\Repositories\Database\Sql\Builders;

use Win\Repositories\Database\Sql\Builder;

/**
 * DELETE FROM ...
 */
class Delete extends Builder
{
	public function __toString()
	{
		return 'DELETE FROM ' . $this->query->table
		. $this->query->where
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