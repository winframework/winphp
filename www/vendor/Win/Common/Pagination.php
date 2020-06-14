<?php

namespace Win\Common;

/**
 * Auxilia criar paginações
 */
class Pagination
{
	public int $current;
	public int $pageSize;
	public int $count = 0;
	public int $prev;
	public int $next;
	public int $last;
	public int $offset;

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
