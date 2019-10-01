<?php

namespace Win\Repositories\Database\Orm;

use Win\Repositories\Database\Sql\Query;

trait SortTrait
{
	/** @var Query */
	protected $query;

	/**
	 * Ordem Crescente por um campo
	 * @param string $column
	 * @param int $priority
	 */
	public function sortAsc($column, $priority = 0)
	{
		$this->query->orderBy->add($column . ' ASC', $priority);

		return $this;
	}

	/**
	 * Ordem Decrescente por um campo
	 * @param string $column
	 * @param int $priority
	 */
	public function sortDesc($column, $priority = 0)
	{
		$this->query->orderBy->add($column . ' DESC', $priority);

		return $this;
	}
}
