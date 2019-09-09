<?php

namespace Win\Database\Sql\Builders;

use Win\Database\Sql\Builder;

/**
 * INSERT INTO ...
 */
class Insert extends Builder
{
	protected $values = null;

	public function __toString()
	{
		return 'INSERT INTO ' . $this->query->table
		. ' (' . implode(',', array_keys($this->getValues())) . ')'
		. ' VALUES (' . implode(', ', $this->getBindParams()) . ')';
	}

	public function getValues()
	{
		if (is_null($this->values)) {
			$this->values = array_merge(
				$this->query->rawValues,
				$this->query->where->values
			);
		}

		return $this->values;
	}

	/**
	 * @return string[]
	 * @example return ['?','?','?']
	 */
	protected function getBindParams()
	{
		return str_split(str_repeat('?', count($this->getValues())));
	}
}
