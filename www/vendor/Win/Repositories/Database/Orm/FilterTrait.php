<?php

namespace Win\Repositories\Database\Orm;

use Win\Repositories\Database\Sql\Query;

trait FilterTrait
{
	/** @var Query */
	protected $query;

	/** @var string */
	public static $PK;

	abstract public function one();

	/**
	 * Aplica um filtro ($comparator aceita mais de uma regra)
	 * @param string $comparator
	 * @param mixed $value
	 * @example filterBy('name = ? AND email = ?', 'John', 'john@email.com')
	 */
	public function filterBy($comparator, ...$values)
	{
		$this->query->where->add($comparator, ...$values);

		return $this;
	}
}
