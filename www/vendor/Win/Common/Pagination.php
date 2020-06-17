<?php

namespace Win\Common;

/**
 * Auxilia criar paginações
 */
class Pagination
{
	public int $current = 1;
	public int $pageSize = 0;
	public int $count;
	public int $last;

	public function offset()
	{
		if ($this->pageSize > 0) {
			$this->last = ceil($this->count / $this->pageSize);
			$this->current = min(max($this->current, 1), $this->last);
		}
		return $this->pageSize * ($this->current - 1);
	}
}
