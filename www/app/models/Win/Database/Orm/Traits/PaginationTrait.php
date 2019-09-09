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
	protected $query;

	/** @return int */
	abstract public function count();

	/**
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
			$offset = $this->pagination->offset();
			$pageSize = $this->pagination->offset();
			$this->query->limit->set($offset, $pageSize);
		}
	}
}
