<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Sql\Query;

trait SortTrait
{
	/** @var Query */
	protected $query;

	/**
	 * Ordena por um campo
	 * @param string $column
	 * @param string $mode
	 * @param int $priority
	 */
	public function sortBy($column, $mode = 'DESC', $priority = 0)
	{
		($this->query)->orderBy->add($column . ' ' . $mode, $priority);

		return $this;
	}
}
