<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Orm\Model;
use Win\Database\Sql\Queries\Select;

/**
 * Comportamento de Ler no banco
 */
trait ReadTrait
{
	/** @return Select */
	abstract protected function getQuery();

	/**
	 * @param mixed[] $row
	 * @return Model
	 */
	abstract public function mapModel($row);

	/**
	 * Retorna o primeiro resultado da busca
	 */
	public function one()
	{
		$rows = $this->getQuery()->execute();
		$result = null;
		if (count($rows)) {
			$result = $this->mapModel($rows[0]);
		}

		return $result;
	}

	/**
	 * Retorna todos os resultado da busca
	 */
	public function all()
	{
		$rows = $this->getQuery()->execute();
		$all = [];
		foreach ($rows as $row) {
			$all[] = $this->mapModel($row);
		}

		return $all;
	}

	/**
	 * Retorna o total de registros da busca
	 * @return int
	 */
	public function count()
	{
		return $this->getQuery()->count();
	}

	/**
	 * Define as colunas da busca
	 * @param string[] $columns
	 * @return static
	 */
	public function setColumns($columns)
	{
		$this->getQuery()->columns = $columns;

		return $this;
	}

	/**
	 * Adiciona uma coluna à busca
	 * @param string $column
	 * @return static
	 */
	public function addColumn($column)
	{
		$this->getQuery()->columns[] = $column;

		return $this;
	}

	/**
	 * Filtra pelo id
	 * @param int $id
	 */
	public function find($id)
	{
		$this->filterBy('id', $id);

		return $this->one();
	}

	/**
	 * Filtra pelo campo
	 * @param string $column
	 * @param mixed $value
	 * @return static
	 */
	public function filterBy($column, $value)
	{
		$this->filter($column, '=', $value);

		return $this;
	}

	/**
	 * Adiciona filtros à busca
	 * @param string $column
	 * @param string $operator
	 * @param mixed $value
	 * @return static
	 */
	public function filter($column, $operator, $value)
	{
		$query = $this->query;
		$query->where()->add($column, $operator, $value);

		return $this;
	}

	/**
	 * Limita os resultados da busca
	 * @param int $limit
	 * @return static
	 */
	public function limit($limit)
	{
		$this->getQuery()->limit($limit);

		return $this;
	}
}
