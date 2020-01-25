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
	 * Aplica um filtro
	 * @param string $comparator
	 * @param mixed $value
	 * @example filterBy('Name = ?', 'John')
	 */
	public function filterBy($comparator, ...$values)
	{
		$this->query->where->add($comparator, ...$values);

		return $this;
	}

}
