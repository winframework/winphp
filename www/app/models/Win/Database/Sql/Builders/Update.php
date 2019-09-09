<?php

namespace Win\Database\Sql\Builders;

use Win\Database\Sql\Builder;

/**
 * UPDATE ... SET ...
 */
class Update extends Builder
{
	public function __toString()
	{
		return 'UPDATE ' . $this->query->table
		. ' SET ' . $this->set()
		. $this->query->where
		. $this->query->limit;
	}

	/** @return string */
	protected function set()
	{
		return implode(', ', array_map(function ($column) {
			return $column . ' = ?';
		}, array_keys($this->query->rawValues)));
	}

	public function getValues()
	{
		return array_merge(
			$this->query->rawValues,
			$this->query->where->values
		);
	}
}
