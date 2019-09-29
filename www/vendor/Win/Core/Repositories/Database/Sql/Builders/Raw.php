<?php

namespace Win\Core\Repositories\Database\Sql\Builders;

use Win\Core\Repositories\Database\Sql\Builder;

/**
 * Executa SQL Puro/Manualmente
 */
class Raw extends Builder
{
	public function __toString()
	{
		return $this->query->raw
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
