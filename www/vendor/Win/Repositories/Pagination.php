<?php

namespace Win\Repositories;

/**
 * Auxilia criar paginações
 */
class Pagination
{
	/** @var int */
	private $pageSize = 0;

	/** @var int */
	private $current = 0;

	/** @var int */
	private $count = 0;

	/**
	 * @param int $pageSize
	 * @param int $currentPage
	 */
	public function setPage($pageSize, $currentPage)
	{
		$this->pageSize = $pageSize;
		$this->current = max($currentPage, 1);
	}

	public function setCount($count)
	{
		$this->count = $count;
		$this->current = min($this->last(), $this->current);
	}

	public function pageSize()
	{
		return $this->pageSize;
	}

	public function count()
	{
		return $this->count;
	}

	public function offset()
	{
		return $this->pageSize * ($this->current - 1);
	}

	public function current()
	{
		return $this->current;
	}

	public function first()
	{
		return 1;
	}

	public function last()
	{
		return ceil($this->count / $this->pageSize);
	}

	public function prev()
	{
		return max(1, $this->current - 1);
	}

	public function next()
	{
		return min($this->last(), $this->current + 1);
	}
}
