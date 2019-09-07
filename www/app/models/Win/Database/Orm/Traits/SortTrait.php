<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Sql\Clauses\OrderBy;
use Win\Database\Sql\Query;

/**
 * @property Query $query
 */
trait SortTrait
{
	/** @var OrderBy */
	protected $orderBy;

	/**
	 * Ordena por um campo
	 * @param string $column
	 * @param string $mode
	 * @param int $priority
	 * @return static
	 */
	public function sortBy($column, $mode = 'DESC', $priority = 0)
	{
		$this->query->orderBy->add($column . ' ' . $mode, $priority);

		return $this;
	}
}
