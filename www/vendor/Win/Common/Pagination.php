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
		$this->offset = $this->pageSize * ($this->current - 1);
	}
}
