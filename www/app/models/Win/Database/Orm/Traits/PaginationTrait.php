<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Orm\Pagination;
use Win\Database\Sql\Query;

/**
 * @property Query $query
 */
trait PaginationTrait
{
	/** @var Pagination */
	public $pagination;

	/** @return int */
	abstract public function count();

	/**
	 * TODO: adicionar paginação
	 * @param int $pageSize
	 * @param int $currentPage
	 */
	public function paginate($pageSize, $currentPage = 1)
	{
		$this->pagination->setPage($pageSize, $currentPage);

		return $this;
	}

	/**
	 * Define um limit válido
	 */
	private function setLimit()
	{
		if ($this->pagination->pageSize()) {
			$this->pagination->setCount($this->count());
			$this->query->limit->set(
				$this->pagination->offset(),
				$this->pagination->pageSize()
			);
		}
	}
}
