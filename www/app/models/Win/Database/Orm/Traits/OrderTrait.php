<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Sql\Queries\Select;

trait OrderTrait
{
	/**
	 * Ordena por um campo
	 * @param string $orderBy
	 * @return static
	 */
	public function orderBy($orderBy)
	{
		return $this;
	}

	/**
	 * Ordena pelo id
	 * @return static
	 */
	public function order($mode = 'DESC')
	{
		$this->orderBy('id ' . $mode);

		return $this;
	}
}
