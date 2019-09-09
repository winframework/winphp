<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Orm\Pagination;
use Win\Database\Sql\Query;

/**
 * Funcionalidade de paginação
 */
trait PaginationTrait
{
	/** @var Pagination */
	public $pagination;

	/** @var Query */
	public $query;

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
			$pagination = $this->pagination;
			$pagination->setCount($this->count());
			$this->query->limit->set(
				$pagination->offset(),
				$pagination->pageSize()
			);
		}
	}
}
