<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Connection;
use Win\Database\Orm\Model;
use Win\Database\Sql\Queries\Select;

trait ReadTrait
{
	/** @var Connection */
	protected static $conn;

	/** @var Select */
	protected $query;

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
		$rows = $this->query->execute();

		return $this->mapModel($rows[0]);
	}

	/**
	 * Retorna todos os resultado da busca
	 */
	public function all()
	{
		$rows = $this->query->execute();
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
		return $this->query->count();
	}

	/**
	 * Define as colunas da busca
	 * @param string[] $columns
	 * @return static
	 */
	public function setColumns($columns)
	{
		$this->query->columns = $columns;

		return $this;
	}

	/**
	 * Adiciona uma coluna à busca
	 * @param string $column
	 * @return static
	 */
	public function addColumn($column)
	{
		$this->query->columns[] = $column;

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
	 * @return static
	 */
	public function filter($column, $operator, $value)
	{
		$query = $this->query;
		$query->where->add($column, $operator, $value);

		return $this;
	}

	/**
	 * Limita os resultados da busca
	 * @param int $limit
	 * @return static
	 */
	public function limit($limit)
	{
		$this->query->limit->set($limit);

		return $this;
	}
}
