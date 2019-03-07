<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Sql\Queries\Select;

/**
 * Comportamento de Ordenar no banco
 */
trait OrderTrait
{
	/** @return Select */
	abstract protected function getQuery();

	/**
	 * Ordena por um campo
	 * @param string $orderBy
	 * @return static
	 */
	public function orderBy($orderBy)
	{
		$this->getQuery()->orderBy($orderBy);

		return $this;
	}

	/**
	 * Ordena pelo id
	 * @param string $mode DESC|ASC
	 * @return static
	 */
	public function order($mode = 'DESC')
	{
		$this->orderBy('id ' . $mode);

		return $this;
	}
}
