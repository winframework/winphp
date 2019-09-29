<?php

namespace Win\Core\Repositories\Database\Orm\Traits;

use Win\Core\Repositories\Database\Sql\Query;

trait FilterTrait
{
	/** @var Query */
	protected $query;

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

	/**
	 * Retorna o registro pela PK
	 * @param int $id
	 */
	public function find($id)
	{
		$this->filterBy(static::PK, $id);

		return $this->one();
	}
}
