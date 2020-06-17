<?php

namespace Win\Common;

/**
 * Auxilia criar paginações
 */
class Pagination
{
	public int $current = 1;
	public ?int $pageSize = null;
	public int $count;
	public int $last;
	public int $offset;

	public function offset()
	{
             if ($this->pageSize) {
		$this->last = ceil($this->count / $this->pageSize);
		$this->current = min(max($this->current, 1), $this->last);
		$this->offset = $this->pageSize * ($this->current - 1);
             }
             return $this->offset;
	}
}
