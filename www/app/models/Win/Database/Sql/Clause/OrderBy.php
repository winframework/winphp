<?php

namespace Win\Database\Sql\Clause;

/**
 * ORDER BY id DESC
 */
class OrderBy
{
	private $orderBy;

	public function __construct()
	{
		$this->orderBy = 'id ASC';
	}

	public function __toString()
	{
		if ($this->orderBy) {
			return ' ORDER BY ' . $this->orderBy;
		}

		return '';
	}

	public function set($orderBy)
	{
		$this->orderBy = $orderBy;
	}
}
