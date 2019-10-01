<?php

namespace Win\Repositories\Database\Sql\Builders;

use Win\Repositories\Database\Sql\Builder;

/**
 * INSERT INTO ...
 */
class Insert extends Builder
{
	public function __toString()
	{
		return 'INSERT INTO ' . $this->query->table
		. ' (' . implode(',', array_keys($this->getValues())) . ')'
		. ' VALUES (' . implode(', ', $this->getBindParams()) . ')';
	}

	public function getValues()
	{
		return $this->query->values;
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
