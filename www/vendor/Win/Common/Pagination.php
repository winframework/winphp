<?php

namespace Win\Common;

/**
 * Auxilia criar paginações
 */
class Pagination
{
	/** @var int */
	public $pageSize;

	/** @var int Current Page */
	public $current;

	/** @var int Total of records */
	public $count = 0;

	public $prev;
	public $next;
	public $last;
	public $offset;

	/**
	 * @param int $pageSize
	 * @param int $current
	 */
	public function __construct($pageSize, $current)
	{
		$this->pageSize = $pageSize;
		$this->current = max($current, 1);
	}

	public function setCount($count)
	{
		$this->count = $count;
		$this->last = ceil($count / $this->pageSize);
		$this->current = min($this->last, $this->current);
		$this->prev = max(1, $this->current - 1);
		$this->next = min($this->last, $this->current + 1);
		$this->offset = $this->pageSize * ($this->current - 1);
	}
}
