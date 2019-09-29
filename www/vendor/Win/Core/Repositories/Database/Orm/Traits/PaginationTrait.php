<?php

namespace Win\Core\Repositories\Database\Orm\Traits;

use Win\Core\Repositories\Database\Orm\Pagination;
use Win\Core\Repositories\Database\Sql\Query;

/**
 * Funcionalidade de paginação
 */
trait PaginationTrait
{
	/** @var Pagination */
	public $pagination;

	/** @var Query */
	protected $query;

	/** @return int */
	abstract public function count();

	/**
	 * @param int $pageSize
	 * @param int $pageNumber
	 */
	public function paginate($pageSize, $pageNumber = 1)
	{
		$this->pagination->setPage($pageSize, $pageNumber);

		return $this;
	}

	/**
	 * Define a paginação se necessário
	 */
	private function applyPagination()
	{
		$count = $this->count();
		$pagination = $this->pagination;

		if ($pagination->pageSize() && $count) {
			$pagination->setCount($count);
			$this->query->limit->set($pagination->offset(), $pagination->pageSize());
		}
	}
}
