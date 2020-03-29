<?php

namespace Win\Repositories\Database\Orm;

use Win\Repositories\Database\Sql\Query;

trait SortTrait
{
	/** @var Query */
	protected $query;

	/**
	 * Ordem por um campo
	 * @param string $column
	 * @param string $mode 'ASC' | 'DESC'
	 * @param int $priority
	 */
	public function sortBy($column, $mode = 'ASC', $priority = 0)
	{
		$this->query->orderBy->add($column . ' ' . $mode, $priority);

		return $this;
	}

	public function sortNewest()
	{
		return $this->sortBy('id', 'DESC');
	}

	public function sortOldest()
	{
		return $this->sortBy('id', 'ASC');
	}

	public function sortRand()
	{
		return $this->sortBy('RAND()');
	}
}
