<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Sql\Query;

trait FilterTrait
{
	/** @var Query */
	protected $query;

	abstract public function one();

	/**
	 * Filtra por campo
	 * @param string $column
	 * @param string $operator
	 * @param mixed $value
	 */
	public function filterBy($column, $operator, $value = null)
	{
		$this->query->where->add($column, $operator, $value);

		return $this;
	}

	/**
	 * Adiciona um filtro por igualdade
	 * @param string $column
	 * @param mixed $value
	 */
	public function filter($column, $value)
	{
		return $this->filterBy($column, '=', $value);
	}

	/**
	 * Retorna o registro pela PK
	 * @param int $id
	 */
	public function find($id)
	{
		$this->filterBy(static::PK, '=', $id);

		return $this->one();
	}
}
